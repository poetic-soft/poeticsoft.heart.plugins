<?php

namespace Poeticsoft\Heart\Babel\Ingestion;

use Poeticsoft\Heart\Babel\Babel;
use Poeticsoft\Heart\Babel\Ingestion\HtmlToMarkdown;
use Poeticsoft\Heart\Babel\VectorStore\ChromaDatabase;
use Poeticsoft\Heart\Babel\Indexing\IndexManager;

/**
 * WordPress Post Ingestor.
 * Handles scanning, converting, and saving tagged WordPress posts/pages to converted markdown storage.
 */
class PostIngestor
{
    /**
     * Base uploads directory for Babel converted posts.
     *
     * @var string
     */
    private $posts_dir;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $upload_dir      = wp_upload_dir();
        $this->posts_dir = trailingslashit($upload_dir['basedir']) . 'babel/converted/posts/';

        $this->ensure_directory();
    }

    /**
     * Initialize hooks for asynchronous background indexing.
     */
    public function init()
    {
        add_action(Babel::PREFIX . 'async_index_file', [$this, 'handle_async_index_file']);
    }

    /**
     * Background callback to index the file asynchronously.
     *
     * @param string $file_path
     */
    public function handle_async_index_file($file_path)
    {
        $index_manager = Babel::get(IndexManager::class);
        $index_manager->index_file($file_path);
    }

    /**
     * Ensure posts converted directory exists.
     */
    private function ensure_directory()
    {
        if (! file_exists($this->posts_dir)) {
            wp_mkdir_p($this->posts_dir);
        }
    }

    /**
     * Get converted posts directory path.
     */
    public function get_converted_posts_dir()
    {
        return $this->posts_dir;
    }

    /**
     * Get target file path for a given post ID.
     *
     * @param int $post_id
     * @return string
     */
    public function get_post_file_path($post_id)
    {
        return $this->posts_dir . 'post-' . intval($post_id) . '.md';
    }

    /**
     * Check if a post should be ingested based on status and taxonomy.
     * Supported statuses: publish, private, future.
     * Must have the 'babel' tag.
     *
     * @param \WP_Post $post
     * @return bool
     */
    public function should_ingest_post($post)
    {
        if (! $post instanceof \WP_Post) {
            return false;
        }

        // Verify status is publish, private, or future.
        $allowed_statuses = ['publish', 'private', 'future'];
        if (! in_array($post->post_status, $allowed_statuses, true)) {
            return false;
        }

        // Verify if it has the 'babel' tag.
        return has_term('babel', 'post_tag', $post);
    }

    /**
     * Synchronize an individual post.
     * Converts it to .md and saves it, or deletes the saved file if untagged/unsupported.
     *
     * @param int|(\WP_Post) $post_id_or_obj Post ID or WP_Post object.
     * @param bool           $async          Whether to index vectorially in background via cron (default: true).
     * @return bool|(\WP_Error) True on successful sync/delete, WP_Error on failure.
     */
    public function sync_post($post_id_or_obj, $async = true)
    {
        $post = is_numeric($post_id_or_obj) ? get_post($post_id_or_obj) : $post_id_or_obj;

        if (! $post instanceof \WP_Post) {
            return new \WP_Error('invalid_post', 'Invalid WordPress post object.');
        }

        $post_id   = $post->ID;
        $file_path = $this->get_post_file_path($post_id);

        // 1. Check if post should be ingested.
        if (! $this->should_ingest_post($post)) {
            // If it should NOT be ingested, but a converted file already exists, we must delete it.
            if (file_exists($file_path)) {
                @unlink($file_path);
                Babel::log("Post-{$post_id} no longer meets ingestion criteria. Deleted converted file.", 'info');

                // Trigger vector removal from ChromaDB.
                $vector_db  = Babel::get(ChromaDatabase::class);
                $collection = get_option(Babel::PREFIX . 'chromadb_collection', Babel::PLUGIN_SLUG);
                $vector_db->delete_documents_by_metadata($collection, ['id' => strval($post_id)]);
            }
            return true;
        }

        // 2. Prepare the Markdown content.
        $markdown_body = HtmlToMarkdown::convert($post->post_content);

        // Get post authors and permalink.
        $author_name = get_the_author_meta('display_name', $post->post_author);
        $permalink   = get_permalink($post_id);

        // Build Front Matter headers in standard YAML format.
        $markdown_content = sprintf(
            "---\nid: \"%d\"\ntitle: \"%s\"\ntype: \"%s\"\nstatus: \"%s\"\nauthor: \"%s\"\ndate: \"%s\"\nurl: \"%s\"\nsource: \"wordpress\"\npath: \"\"\n---\n# %s\n\n%s",
            $post_id,
            str_replace('"', '\"', $post->post_title),
            $post->post_type,
            $post->post_status,
            str_replace('"', '\"', $author_name),
            $post->post_date,
            $permalink,
            $post->post_title,
            $markdown_body
        );

        // 3. Save the converted Markdown file.
        $this->ensure_directory();
        $saved = file_put_contents($file_path, $markdown_content);

        if (false === $saved) {
            Babel::log("Failed to save converted Markdown for Post-{$post_id}", 'error');
            return new \WP_Error('write_failed', "Could not write converted Markdown for Post-{$post_id}.");
        }

        @chmod($file_path, 0664);
        Babel::log("Successfully synchronized and converted Post-{$post_id} to Markdown.", 'success');

        // 4. Index the file vectorially.
        if ($async) {
            // Schedule async cron single-event in 5 seconds to avoid blocking HTTP threat/Gutenberg.
            if (! wp_next_scheduled(Babel::PREFIX . 'async_index_file', [$file_path])) {
                wp_schedule_single_event(time() + 5, Babel::PREFIX . 'async_index_file', [$file_path]);
                Babel::log("Scheduled background vector indexing for: " . basename($file_path), 'info');
            }
        } else {
            // Synchronous indexing (e.g. from manual admin sync button click).
            $index_manager = Babel::get(IndexManager::class);
            $indexed       = $index_manager->index_file($file_path);

            if (is_wp_error($indexed)) {
                Babel::log("Post-{$post_id} converted but vector indexing failed: " . $indexed->get_error_message(), 'error');
            }
        }

        return true;
    }

    /**
     * Delete a synchronized post file.
     *
     * @param int $post_id
     * @return bool
     */
    public function delete_post($post_id)
    {
        $file_path = $this->get_post_file_path($post_id);

        if (file_exists($file_path)) {
            @unlink($file_path);
            Babel::log("Post-{$post_id} deleted. Removed converted Markdown file.", 'info');

            // Trigger vector removal from ChromaDB.
            $vector_db  = Babel::get(ChromaDatabase::class);
            $collection = get_option(Babel::PREFIX . 'chromadb_collection', Babel::PLUGIN_SLUG);
            $vector_db->delete_documents_by_metadata($collection, ['id' => strval($post_id)]);

            return true;
        }

        return false;
    }

    /**
     * Query and synchronize all posts/pages/CPTs tagged with 'babel' and in supported statuses.
     *
     * @return array Ingestion stats.
     */
    public function sync_all_tagged_posts()
    {
        // Get all public and private post types.
        $post_types = get_post_types(['public' => true]);

        $query_args = [
            'post_type'      => array_values($post_types),
            'post_status'    => ['publish', 'private', 'future'],
            'posts_per_page' => -1,
            'tag'            => 'babel', // Query by tag slug.
            'fields'         => 'ids',
        ];

        $query = new \WP_Query($query_args);
        $post_ids = $query->posts;

        $results = [
            'success' => 0,
            'failed'  => [],
        ];

        if (empty($post_ids)) {
            return $results;
        }

        foreach ($post_ids as $id) {
            $synced = $this->sync_post($id, false);
            if (is_wp_error($synced)) {
                $results['failed'][] = [
                    'id'      => $id,
                    'message' => $synced->get_error_message(),
                ];
            } else {
                $results['success']++;
            }
        }

        return $results;
    }

    /**
     * Get list of post IDs that are tagged with 'babel' but are either:
     * 1. Not yet converted/saved to Markdown.
     * 2. Converted, but the post was modified after the Markdown file was written.
     *
     * @return array Array of pending post IDs.
     */
    public function get_pending_posts()
    {
        $post_types = get_post_types(['public' => true]);

        $query_args = [
            'post_type'      => array_values($post_types),
            'post_status'    => ['publish', 'private', 'future'],
            'posts_per_page' => -1,
            'tag'            => 'babel',
            'fields'         => 'ids',
        ];

        $query = new \WP_Query($query_args);
        $post_ids = $query->posts;

        $pending_ids = [];

        foreach ($post_ids as $id) {
            $file_path = $this->get_post_file_path($id);

            if (! file_exists($file_path)) {
                $pending_ids[] = $id;
            } else {
                $post = get_post($id);
                if ($post) {
                    $post_modified_time = mysql2date('U', $post->post_modified_gmt);
                    $file_modified_time = filemtime($file_path);

                    if ($post_modified_time > $file_modified_time) {
                        $pending_ids[] = $id;
                    }
                }
            }
        }

        return $pending_ids;
    }
}
