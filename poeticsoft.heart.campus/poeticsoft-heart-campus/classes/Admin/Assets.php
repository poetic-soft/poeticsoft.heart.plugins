<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Utils\Utils;

class Assets
{
    public function init()
    {
        add_action(
            'admin_enqueue_scripts',
            [$this, 'enqueue_scripts']
        );

        add_action(
            'enqueue_block_assets',
            [$this, 'enqueue_block_assets']
        );
    }


    public function enqueue_scripts($hook_suffix)
    {

        $screen = get_current_screen();

        $screen_id = sprintf('%1$s_%2$s', $screen->base, $screen->id);
        $page_access = sprintf('%s_page_%saccess', Campus::PLUGIN_SLUG, Campus::PREFIX);

        $enqueue_handle = Campus::PLUGIN_SLUG . 'admin-';
        $enqueue_url = 'ui/admin/';

        $enqueue_data = null;


        switch ($hook_suffix) {
            case $page_access:
                $enqueue_data = [
                    'handle' => $enqueue_handle . 'access',
                    'url' => $enqueue_url . 'access/main.',
                    'enqueue' => $page_access
                ];

                $this->enqueue($enqueue_data);

                break;
        }


        switch ($screen_id) {
            case 'edit_edit-page':
                $page_utils_enabled_option_name = sprintf('%spage_utils', Campus::PREFIX);
                $page_utils_enabled = get_option($page_utils_enabled_option_name);
                if ($page_utils_enabled) {
                    $enqueue_data = [
                        'handle' => $enqueue_handle . 'pageslist',
                        'url' => $enqueue_url . 'pageslist/main.',
                        'enqueue' => $screen_id
                    ];

                    $this->enqueue($enqueue_data);
                } else {
                    $enqueue_data = [
                        'handle' => $enqueue_handle . 'pagesquickedit',
                        'url' => $enqueue_url . 'pagesquickedit/main.',
                        'enqueue' => $screen_id
                    ];

                    $this->enqueue($enqueue_data);
                }

                break;
        }

        if ($enqueue_data) {
            $this->add_inline_script($enqueue_data);
        }
    }

    private function enqueue($enqueue_data)
    {

        wp_enqueue_style(
            $enqueue_data['handle'],
            Utils::url($enqueue_data['url'] . 'css'),
            array_merge([], $enqueue_data['css_deps'] ?? []),
            Campus::VERSION
        );

        wp_enqueue_script(
            $enqueue_data['handle'],
            Utils::url($enqueue_data['url'] . 'js'),
            array_merge(['jquery'], $enqueue_data['js_deps'] ?? []),
            Campus::VERSION,
            true
        );
    }


    private function add_inline_script($enqueue_data)
    {

        switch ($enqueue_data['enqueue']) {
            case 'edit_edit-page':
                $pages = get_posts([
                    'post_type'      => 'page',
                    'post_status'    => 'any',
                    'fields'         => 'ids',
                    'posts_per_page' => -1,
                ]);

                $page_ids = [];
                foreach ($pages as $page_id) {
                    $page_ids['post-' . $page_id] = array_map(
                        function ($child) {

                            return 'post-' . $child;
                        },
                        get_children([
                            'post_parent' => $page_id,
                            'post_type'   => 'page',
                            'fields'      => 'ids',
                        ])
                    );
                }

                $data_json = json_encode($page_ids);
                $prefix = Campus::PREFIX;
                $inline_js = "var {$prefix}admin_pageslist = {$data_json};";
                wp_add_inline_script(
                    $enqueue_data['handle'],
                    $inline_js,
                    'after'
                );



                $campus_root_id_option_name = sprintf('%sroot_post_id', Campus::PREFIX);
                $campus_root_id = intval(get_option($campus_root_id_option_name));
                if (!$campus_root_id) {
                    return;
                }

                $descendants = get_pages([
                'child_of' => $campus_root_id,
                'post_type' => 'page',
                'post_status' => [
                    'publish',
                    'private',
                    'pending',
                    'draft',
                    'future'
                ]
                ]);
                $descendant_ids = wp_list_pluck($descendants, 'ID');
                $descendant_ids[] = $campus_root_id;
                $campus_ids = array_map(
                    function ($id) {

                        return 'post-' . $id;
                    },
                    $descendant_ids
                );

                $data_json = json_encode($campus_ids);
                $prefix = Campus::PREFIX;
                $inline_js = "var {$prefix}admin_campus_ids = {$data_json};";
                wp_add_inline_script(
                    $enqueue_data['handle'],
                    $inline_js,
                    'after'
                );

                break;
        }
    }

    public function enqueue_block_assets()
    {

        wp_enqueue_style('dashicons');
    }
}
