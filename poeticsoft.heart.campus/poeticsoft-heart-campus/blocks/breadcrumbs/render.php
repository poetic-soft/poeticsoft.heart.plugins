<?php

/**
 * - $attributes: atributos del bloque
 * - $content: contenido interno, si aplica
 * - $block: array con info completa del bloque
 */

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Validation\Validation;

defined('ABSPATH') || exit;

// 1. Validar y Sanitizar Atributos
$validator = Campus::get(Validation::class);
$schema = [
    'blockId'     => ['type' => 'text', 'required' => false],
    'refClientId' => ['type' => 'text', 'required' => false],
];

$attrs = $validator->validate_schema($attributes, $schema);

if (is_wp_error($attrs)) {
    $attrs = $attributes;
}

$breadcrumbs = '';

if (is_single() || is_page()) {
    global $post;

    if (!$post) {
        return;
    }

    $campus_root_id_option_name = sprintf('%sroot_post_id', Campus::PREFIX);
    $campus_root_id = get_option($campus_root_id_option_name);

    $separator = '<span class="Separator">&raquo;</span>';

    $ancestors = get_post_ancestors($post);
    $ancestors = array_reverse($ancestors);
    $breadcrumbs = implode(
        $separator,
        array_filter(
            array_map(
                function ($id) use ($campus_root_id) {
                    $permalink = get_permalink($id);
                    if (!$permalink) {
                        return '';
                    }

                    return $id == $campus_root_id ?
                        sprintf('<a class="Root" aria-label="%s" href="%s"></a>', esc_attr(__('Campus', Campus::TEXT_DOMAIN)), esc_url($permalink)) :
                        sprintf(
                            '<a class="Page" href="%s">%s</a>',
                            esc_url($permalink),
                            esc_html(get_the_title($id))
                        );
                },
                $ancestors
            )
        )
    );

    $breadcrumbs .= count($ancestors) ?
        $separator . sprintf('<span class="Actual">%s</span>', esc_html(get_the_title())) :
        '<span class="Root">&#128218;</span>';
}

$wrapper_attributes = get_block_wrapper_attributes([
    'id' => $attrs['blockId'] ?? '',
]);

printf(
    '<div %s>%s</div>',
    $wrapper_attributes,
    $breadcrumbs
);
