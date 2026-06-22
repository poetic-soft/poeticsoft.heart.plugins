<?php

namespace Poeticsoft\Heart\Babel\Admin;

use Poeticsoft\Heart\Babel\Babel;
use Poeticsoft\Heart\Babel\Ingestion\PostIngestor;

/**
 * Post Editor Controller.
 * Handles real-time synchronization hooks specifically for the WordPress post/page editor.
 */
class PostEditorController
{
    /**
     * Initialize editor hooks.
     */
    public function init()
    {
        Babel::log('PostEditorController initialized', 'debug');

        // 1. Hook into saving process for real-time synchronization.
        add_action('save_post', [$this, 'handle_post_save'], 10, 3);

        // 2. Hook into trashing and deleting process to remove local files.
        add_action('wp_trash_post', [$this, 'handle_post_deletion']);
        add_action('before_delete_post', [$this, 'handle_post_deletion']);
    }

    /**
     * Sync post to Markdown when saved or status transitions.
     *
     * @param int      $post_id Post ID.
     * @param \WP_Post $post    Post object.
     * @param bool     $update  Whether this is an existing post being updated.
     */
    public function handle_post_save($post_id, $post, $update)
    {
        // Ignore autosaves.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Ignore post revisions.
        if (wp_is_post_revision($post_id)) {
            return;
        }

        // Check user permissions.
        if (! current_user_can('edit_post', $post_id)) {
            return;
        }

        // Delegate synchronization to PostIngestor.
        Babel::get(PostIngestor::class)->sync_post($post);
    }

    /**
     * Delete Markdown files when posts are trashed or deleted permanently.
     *
     * @param int $post_id
     */
    public function handle_post_deletion($post_id)
    {
        // Delegate deletion to PostIngestor.
        Babel::get(PostIngestor::class)->delete_post($post_id);
    }
}
