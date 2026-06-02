<?php

/**
 * - $attributes: atributos del bloque
 * - $content: contenido interno, si aplica
 * - $block: array con info completa del bloque
 */

defined('ABSPATH') || exit;

use Poeticsoft\Heart\Campus;
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
    'includesMode'       => ['type' => 'key',  'required' => false],
    'tags'               => ['type' => 'text', 'required' => false], // JSON string
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

    $includes_mode = $attrs['includesMode'] ?? 'related';
    $mode = $attrs['mode'] ?? 'compact';
    $tags_raw = $attrs['tags'] ?? '[]';
    $section_heading_tag = tag_escape($attrs['sectionHeadingType'] ?? 'h2');
    $area_heading_tag = tag_escape($attrs['areaHeadingType'] ?? 'h3');
    $title = $attrs['title'] ?? '';
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

    $tags_decoded = json_decode($tags_raw, true);
    $tag_ids_input = is_array($tags_decoded) ? $tags_decoded : [];
    $final_tag_ids = [];

    if ($includes_mode === 'related' || $includes_mode === 'relatedandtags') {
        $post_tags = wp_get_post_tags($post->ID, ['fields' => 'ids']);
        if (!is_wp_error($post_tags)) {
            $final_tag_ids = array_merge($final_tag_ids, $post_tags);
        }
    }

    if ($includes_mode === 'tags' || $includes_mode === 'relatedandtags') {
        $final_tag_ids = array_merge($final_tag_ids, $tag_ids_input);
    }

    $final_tag_ids = array_unique($final_tag_ids);
    $results = [];

    if (!empty($final_tag_ids)) {
        $query = new WP_Query([
            'post_type'      => 'page',
            'posts_per_page' => -1,
            'post__not_in'   => [$post->ID],
            'tag__in'        => $final_tag_ids,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ]);
        $results = $query->posts;
    }

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
