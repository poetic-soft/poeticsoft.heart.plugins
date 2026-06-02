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
    'blockId'   => ['type' => 'text', 'required' => false],
    'linkType'  => ['type' => 'key',  'required' => false],
    'idVisible' => ['type' => 'bool', 'required' => false],
];

$attrs = $validator->validate_schema($attributes, $schema);

if (is_wp_error($attrs)) {
    $attrs = $attributes;
}

// 2. Lógica de Negocio
$valid_user_mail = Campus::get(Access::class)->validate_email();

if ($valid_user_mail) {

    $logout_url = add_query_arg(['action' => 'logout'], get_permalink($post->ID));

    $element = '';
    switch ($attrs['linkType']) {

        case 'button':
            $element = sprintf(
                '<button class="wp-block-button__link wp-element-button"><a href="%s">SALIR</a></button>',
                esc_url($logout_url)
            );
            
            $element = sprintf(
                '<div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
                    <div class="wp-block-button">
                        <a 
                            href="%s" 
                            class="wp-block-button__link wp-element-button"
                        >
                            SALIR
                        </a>
                    </div>
                </div>',
                esc_url($logout_url)
            );
            break;

        case 'link':
        default:
            $element = sprintf(
                '<a href="%s">SALIR</a>',
                esc_url($logout_url)
            );
            break;
    }

    $identify = $attrs['idVisible'] ?
        sprintf('<span class="Identify">%s</span>', esc_html($valid_user_mail)) :
        '';

    $link = sprintf('<span class="Logout">%s</span>', $element);

    $wrapper_attributes = get_block_wrapper_attributes([
        'id'    => $attrs['blockId'] ?? '',
        'class' => 'is-authenticated'
    ]);

    printf(
        '<div %s>%s%s</div>',
        $wrapper_attributes,
        $identify,
        $link
    );

} else {

    $element = '';
    switch ($attrs['linkType'] ?? 'link') {

        case 'button':
            $element = '<div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
                <div class="wp-block-button">
                    <a 
                        href="#" 
                        class="wp-block-button__link wp-element-button Login"
                    >
                        ENTRAR
                    </a>
                </div>
            </div>';
            break;

        case 'link':
        default:
            $element = '<a 
                href="#" 
                class="Login"
            >
                ENTRAR
            </a>';
            break;
    }

    $wrapper_attributes = get_block_wrapper_attributes([
        'id'    => $attrs['blockId'] ?? '',
        'class' => 'is-anonymous'
    ]);

    printf(
        '<div %s>%s</div>',
        $wrapper_attributes,
        $element
    );
}
