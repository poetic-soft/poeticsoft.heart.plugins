<?php

/**
 * - $attributes: atributos del bloque
 * - $content: contenido interno, si aplica
 * - $block: array con info completa del bloque
 */

defined('ABSPATH') || exit;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Validation\Validation;

// 1. Validar y Sanitizar Atributos
$validator = Campus::get(Validation::class);
$schema = [
    'blockId'     => ['type' => 'text', 'required' => false],
    'refClientId' => ['type' => 'text', 'required' => false],
    'defaultOpen' => ['type' => 'bool', 'required' => false],
];

$attrs = $validator->validate_schema($attributes, $schema);

if (is_wp_error($attrs)) {
    $attrs = $attributes;
}

$wrapper_attributes = get_block_wrapper_attributes([
    'id'               => $attrs['blockId'] ?? '',
    'data-defaultopen' => $attrs['defaultOpen'] ? 1 : 0
]);

printf(
    '<div %s><span class="OpenClose"></span></div>',
    $wrapper_attributes
);
