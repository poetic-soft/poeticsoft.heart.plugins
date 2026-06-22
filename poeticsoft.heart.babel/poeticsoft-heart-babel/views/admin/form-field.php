<?php
/**
 * Generic Form Field Template.
 * 
 * @var \Poeticsoft\Heart\Babel\Admin\AbstractPage $page
 * @var string $option_name
 * @var mixed  $value
 * @var string $type
 * @var string $description
 * @var array  $options (optional, for select)
 */

switch ( $type ) {
        case 'checkbox':
                printf(
                        '<input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s />',
                        esc_attr( $option_name ),
                        checked( 1, $value, false )
                );
                break;

        case 'select':
                printf( '<select id="%1$s" name="%1$s">', esc_attr( $option_name ) );
                if ( ! empty( $options ) ) {
                        foreach ( $options as $option ) {
                                printf(
                                        '<option value="%1$s" %2$s>%3$s</option>',
                                        esc_attr( $option['value'] ),
                                        selected( $value, $option['value'], false ),
                                        esc_html( $option['label'] )
                                );
                        }
                }
                echo '</select>';
                break;

        case 'textarea':
                printf(
                        '<textarea id="%1$s" name="%1$s" rows="5" cols="50" class="large-text">%2$s</textarea>',
                        esc_attr( $option_name ),
                        esc_textarea( $value )
                );
                break;

        case 'text':
        case 'password':
        case 'number':
        case 'email':
        default:
                printf(
                        '<input type="%1$s" id="%2$s" name="%2$s" value="%3$s" class="regular-text" />',
                        esc_attr( $type ),
                        esc_attr( $option_name ),
                        esc_attr( $value )
                );
                break;
}

if ( ! empty( $description ) ) {
        printf( '<p class="description">%s</p>', esc_html( $description ) );
}
