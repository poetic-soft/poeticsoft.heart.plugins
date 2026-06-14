<?php

namespace Poeticsoft\Heart\Blocks;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Validation\Access;
use Poeticsoft\Heart\Utils\Utils;
use Poeticsoft\Heart\View\View;

class PostContent
{
    public function __construct()
    {
        add_action(
            'wp_enqueue_scripts',
            [$this, 'enqueue_scripts']
        );

        add_filter(
            'block_type_metadata_settings',
            [$this, 'post_content_metadata_settings'],
            10,
            2
        );

        add_filter(
            'render_block_core/post-content',
            [$this, 'post_content_render'],
            9999,
            2
        );
    }

    public function enqueue_scripts()
    {
        global $post;
        if (
            !$post
            ||
            !Campus::get(Access::class)->post_is_in_campus($post->ID)
        ) {
            return;
        }

        wp_enqueue_script(
            Campus::PLUGIN_SLUG . '-postcontent',
            Utils::url('ui/frontend/postcontent/main.js'),
            [
                'jquery'
            ],
            filemtime(Utils::path('ui/frontend/postcontent/main.js')),
            true
        );

        wp_enqueue_style(
            Campus::PLUGIN_SLUG . '-postcontent',
            Utils::url('ui/frontend/postcontent/main.css'),
            [],
            filemtime(Utils::path('ui/frontend/postcontent/main.css')),
            'all'
        );

        wp_enqueue_script(
            Campus::PLUGIN_SLUG . '-register-access',
            Utils::url('ui/frontend/registeraccess/main.js'),
            [
                'jquery'
            ],
            filemtime(Utils::path('ui/frontend/registeraccess/main.js')),
            true
        );

        $validate_email = Campus::get(Access::class)->validate_email();
        $email = $validate_email ? $validate_email : 'anonymous';
        $ip = Utils::get_request_ip();
        $accessdata = $post->ID . '||' . $email . '||' . $ip;
        $campus_access_data_key = Campus::PREFIX . 'register_access_data';
        $inline_js = "var {$campus_access_data_key} = '{$accessdata}';";
        wp_add_inline_script(
            Campus::PLUGIN_SLUG . '-register-access',
            $inline_js,
            'before'
        );
    }

    public function post_content_metadata_settings($settings, $metadata)
    {
        global $post;
        if (
            !$post
            ||
            !Campus::get(Access::class)->post_is_in_campus($post->ID)
        ) {
            return $settings;
        }

        if (
            isset($metadata['name'])
            &&
            $metadata['name'] === 'core/post-content'
        ) {
            if (!isset($settings['style'])) {
                $settings['style'] = [];
            }

            $settings['style'][] = 'wp-block-button';
            $settings['style'][] = 'wp-block-buttons';
            $settings['style'] = array_unique($settings['style']);
        }

        return $settings;
    }

    public function post_content_render($block_content, $block)
    {
        global $post;

        if (!$post) {
            return;
        }

        if (!Campus::get(Access::class)->post_is_in_campus($post->ID)) {
            return $block_content;
        }

        if (Campus::get(Access::class)->can_access($post->ID)) {
            return $this->render_access_messages($block_content, $post->ID);
        }

        return $this->render_access_form(
            $post->ID,
            $block['attrs']
        );
    }

    private function render_access_messages($block_content, $post_id)
    {
        if (
            current_user_can('manage_options')
            &&
            Campus::get(Utils::class)->get_allow_admin()
        ) {
            $banner = Campus::get(View::class)->render('frontend/admin-view-banner', [], false);
            return $banner . $block_content;
        }

        $valid_user_mail = Campus::get(Access::class)->validate_email();
        if ($valid_user_mail) {
            global $wpdb;
            $table_name = $wpdb->prefix . Campus::PREFIX . 'last_access';

            $wpdb->insert(
                $table_name,
                [
                    'user_mail'        => $valid_user_mail,
                    'post_id'          => $post_id,
                    'last_access_date' => current_time('mysql'),
                ],
                [
                    '%s',
                    '%d',
                    '%s'
                ]
            );
        }

        return $block_content;
    }

    private function render_access_form($post_id, $block_attrs)
    {
        $show_restricted_text = isset($block_attrs['showRestrictedText']) ?
            $block_attrs['showRestrictedText'] : '';
        $post_child_ids = get_posts([
            'post_type' => 'page',
            'posts_per_page' => -1,
            'post_parent' => $post_id,
            'fields' => 'ids'
        ]);

        if (
            $show_restricted_text == 'hiddenalways'
            ||
            (
                $show_restricted_text == 'onlyincontents'
                &&
                count($post_child_ids)
            )
        ) {
            return '';
        }

        $restricted_visible_text = isset($block_attrs['restrictedVisibleText']) ?
            $block_attrs['restrictedVisibleText']
            :
            __('El contenido de esta página está restringido, solicita acceso.', Campus::TEXT_DOMAIN);

        $valid_user_mail = Campus::get(Access::class)->validate_email();

        return Campus::get(View::class)->render('frontend/post-content-access', [
            'valid_user_mail'          => $valid_user_mail,
            'post_id'                  => $post_id,
            'restricted_visible_text'  => $restricted_visible_text,
        ], false);
    }
}
