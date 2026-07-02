<?php

/**
 * - $attributes: atributos del bloque
 * - $content: contenido interno, si aplica
 * - $block: array con info completa del bloque
 */

defined('ABSPATH') || exit;

$wrapper_attributes = get_block_wrapper_attributes();
$element = '<span class="base">base</a>';

printf(
    '<div %s>%s</div>',
    $wrapper_attributes,
    $element
);