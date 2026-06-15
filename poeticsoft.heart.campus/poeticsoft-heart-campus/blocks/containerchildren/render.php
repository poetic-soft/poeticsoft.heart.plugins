<?php

/**
 * - $attributes: atributos del bloque
 * - $content: contenido interno, si aplica
 * - $block: array con info completa del bloque
 */

defined('ABSPATH') || exit;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Validation\Access;
use Poeticsoft\Heart\Validation\Validation;

global $post;

if (!$post) {
    return;
}

// 1. Validar y Sanitizar Atributos
$validator = Campus::get(Validation::class);
$schema = [
    'blockId'            => ['type' => 'text', 'required' => false],
    'refClientId'        => ['type' => 'text', 'required' => false],
    'title'              => ['type' => 'text', 'required' => false],
    'sectionHeadingType' => ['type' => 'key',  'required' => false],
    'areaHeadingType'    => ['type' => 'key',  'required' => false],
    'contents'           => ['type' => 'key',  'required' => false],
    'mode'               => ['type' => 'key',  'required' => false],
];

$attrs = $validator->validate_schema($attributes, $schema);

if (is_wp_error($attrs)) {
    $attrs = $attributes;
}

$valid_user_email = Campus::get(Access::class)->validate_email();
$block_id = $attrs['blockId'] ?? '';
$cache_enabled = (bool) get_option(Campus::PREFIX . 'block_cache_enabled', true);
$cache_key = ($block_id && $cache_enabled) ? 'poeticsoft_heart_campus_' . md5($block_id . '_' . $post->ID . '_' . $valid_user_email) : '';
$dom = '';

if ($cache_key) {
    $dom = get_transient($cache_key);
}

if (false === $dom || empty($cache_key)) {
    $mode = $attrs['mode'] ?? 'compact';
    $contents = $attrs['contents'] ?? 'subscriptionsandopen';
    $section_heading_tag = tag_escape($attrs['sectionHeadingType'] ?? 'h3');
    $area_heading_tag = tag_escape($attrs['areaHeadingType'] ?? 'h4');
    $title = $attrs['title'] ?? '';

    $child_ids = get_posts([
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_parent'    => $post->ID,
        'fields'         => 'ids'
    ]);

    $dom = '';

    if (count($child_ids)) {
        switch ($contents) {
            case 'allidentified':
                if (!$valid_user_email) {
                    $child_ids = [];
                }
                break;

            case 'subscriptionsandopen':
                $child_ids = array_values(
                    array_filter(
                        $child_ids,
                        function ($id) {
                            return Campus::get(Access::class)->can_access($id)
                                || Campus::get(Access::class)->can_access_cause_child_accessible($id);
                        }
                    )
                );
                break;
        }

        if (count($child_ids)) {
            $posts_data = get_posts([
                'post__in'       => $child_ids,
                'post_type'      => 'page',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC'
            ]);

            $title_dom = '';
            if ($title) {
                $title_dom = sprintf(
                    '<%1$s class="Title">%2$s</%1$s>',
                    $section_heading_tag,
                    esc_html($title)
                );
            }

            $area_pages_dom = '';
            foreach ($posts_data as $child_post) {
                $permalink = get_permalink($child_post->ID);
                if (!$permalink) {
                    continue;
                }

                $page_dom = sprintf(
                    '<div class="Area"><%1$s class="Title"><a href="%2$s">%3$s</a></%1$s>',
                    $area_heading_tag,
                    esc_url($permalink),
                    esc_html(get_the_title($child_post->ID))
                );

                switch ($mode) {
                    case 'complete':
                        $thumb_url = get_the_post_thumbnail_url($child_post->ID, 'full');
                        $thumb_dom = $thumb_url ? sprintf('<img src="%s" alt="%s">', esc_url($thumb_url), esc_attr(get_the_title($child_post->ID))) : '';

                        $page_dom .= sprintf(
                            '<div class="Image"><a href="%1$s">%2$s</a></div><div class="Excerpt">%3$s</div>',
                            esc_url($permalink),
                            $thumb_dom,
                            esc_html(get_the_excerpt($child_post->ID))
                        );

                        break;

                    case 'contents':
                        $child_children_pages = get_pages([
                            'parent'     => $child_post->ID,
                            'post_type'  => 'page',
                            'sort_column' => 'menu_order'
                        ]);

                        $page_dom .= implode(
                            '',
                            array_map(
                                function ($page) {
                                    return '<div class="ChildChildPage">
                                        <a href="' . get_permalink($page->ID) . '">
                                            ' . $page->post_title . '
                                        </a>
                                    </div>';
                                },
                                $child_children_pages
                            )
                        );

                        break;
                }

                $page_dom .= '</div>';
                $area_pages_dom .= $page_dom;
            }

            $wrapper_attributes = get_block_wrapper_attributes([
                'id' => $block_id,
            ]);

            $dom = sprintf(
                '<div %s>%s<div class="Areas">%s</div></div>',
                $wrapper_attributes,
                $title_dom,
                $area_pages_dom
            );
        }
    }

    if ($cache_key) {
        set_transient($cache_key, $dom, 0);
    }
}

echo $dom;
