<?php
/**
 * Generic Form Field Template.
 * 
 * @var \Poeticsoft\Heart\Admin\Page $page
 * @var string $option_name
 * @var mixed  $value
 * @var string $type
 * @var string $description
 * @var array  $options (optional, for select)
 */

$width = $width ? 'style="width: ' . $width . 'px;"' : '';

switch ( $type ) {
	case 'checkbox':
		printf(
			'<input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s %3$s />',
			esc_attr( $option_name ),
			checked( 1, $value, false ),
            $width
		);
		break;

	case 'select':
		printf( '<select id="%1$s" name="%1$s" %2$s>', esc_attr( $option_name ), $width );
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
			'<textarea id="%1$s" name="%1$s" rows="5" cols="50" class="large-text" %3$s>%2$s</textarea>',
			esc_attr( $option_name ),
			esc_textarea( $value ),
            $width
		);
		break;

	case 'text':
	case 'password':
	case 'number':
	case 'email':
	default:
		printf(
			'<input type="%1$s" id="%2$s" name="%2$s" value="%3$s" class="regular-text" %4$s/>',
			esc_attr( $type ),
			esc_attr( $option_name ),
			esc_attr( $value ),
            $width
		);
		break;
}

if ( ! empty( $description ) ) {
	printf( '<p class="description">%s</p>', esc_html( $description ) );
}
