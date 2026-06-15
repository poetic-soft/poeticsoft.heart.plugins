<?php

/**
 * - $attributes: atributos del bloque
 * - $content: contenido interno, si aplica
 * - $block: array con info completa del bloque
 */

defined('ABSPATH') || exit;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Validation\Validation;
use Poeticsoft\Heart\Validation\Access;
use Poeticsoft\Heart\Utils\Utils;

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
    'maxCount'           => ['type' => 'number', 'required' => false],
    'sectionHeadingType' => ['type' => 'key',  'required' => false],
    'areaHeadingType'    => ['type' => 'key',  'required' => false], // JSON string
    'mode'               => ['type' => 'key',  'required' => false],
    'visibility'         => ['type' => 'key',  'required' => false],
];

$attrs = $validator->validate_schema($attributes, $schema);

if (is_wp_error($attrs)) {
    $attrs = $attributes;
}

$block_id = $attrs['blockId'] ?? '';
$cache_enabled = (bool) get_option(Campus::PREFIX . 'block_cache_enabled', true);
$cache_key = ($block_id && $cache_enabled) ? 'poeticsoft_heart_campus_' . md5($block_id . '_' . $post->ID) : '';
$related_dom = '';

if ($cache_key) {
    $related_dom = get_transient($cache_key);
}

if (false === $related_dom || empty($cache_key)) {
    $mode = $attrs['mode'] ?? 'compact';
    $section_heading_tag = tag_escape($attrs['sectionHeadingType'] ?? 'h2');
    $area_heading_tag = tag_escape($attrs['areaHeadingType'] ?? 'h3');
    $title = $attrs['title'] ?? '';
    $max_count = intval($attrs['maxCount']);
    $visibility = $attrs['visibility'] ?? 'visiblealways';
    $campus_root_id = absint(get_option(Campus::PREFIX . 'root_post_id', 0));

    $has_children = count(get_posts([
        'post_type'      => 'page',
        'posts_per_page' => 1,
        'post_parent'    => $campus_root_id,
        'fields'         => 'ids'
    ])) > 0;

    if (!$has_children && $visibility === 'onlyincontainers') {
        return;
    }

    if (!Campus::get(Access::class)->validate_email()) {

        return;
    }

    $results = Campus::get(Access::class)->all_user_access_posts($max_count);

    if (count($results)) {
        $title_dom = '';
        if ($title) {
            $title_dom = sprintf(
                '<%1$s class="Title">%2$s</%1$s>',
                $section_heading_tag,
                esc_html($title)
            );
        }

        $areas_dom = '';
        foreach ($results as $result_post) {
            $permalink = get_permalink($result_post->ID);
            if (!$permalink) {
                continue;
            }

            $area_dom = sprintf(
                '<div class="Area"><%1$s class="Title"><a href="%2$s">%3$s</a></%1$s>',
                $area_heading_tag,
                esc_url($permalink),
                esc_html($result_post->post_title)
            );

            if ($mode === 'complete') {
                $thumb_url = get_the_post_thumbnail_url($result_post->ID, 'full');
                $thumb_dom = $thumb_url ? sprintf('<img src="%s" alt="%s">', esc_url($thumb_url), esc_attr($result_post->post_title)) : '';

                $area_dom .= sprintf(
                    '<div class="Image"><a href="%1$s">%2$s</a></div><div class="Excerpt">%3$s</div>',
                    esc_url($permalink),
                    $thumb_dom,
                    esc_html($result_post->post_excerpt)
                );
            }

            $area_dom .= '</div>';
            $areas_dom .= $area_dom;
        }

        $related_dom = sprintf('%s<div class="Areas">%s</div>', $title_dom, $areas_dom);
    }

    if ($cache_key) {
        set_transient($cache_key, $related_dom, 0);
    }
}

$wrapper_attributes = get_block_wrapper_attributes([
    'id' => $block_id,
]);

printf(
    '<div %s>%s</div>',
    $wrapper_attributes,
    $related_dom
);
