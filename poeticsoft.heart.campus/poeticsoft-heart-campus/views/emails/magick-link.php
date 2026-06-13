<?php

/**
 * Magick Link Email View.
 *
 * @package Poeticsoft\Heart
 */

use Poeticsoft\Heart\Campus;

if (! defined('ABSPATH')) {
    exit;
}
?>
<p><?php _e('usa este link para acceder:', Campus::TEXT_DOMAIN); ?> <a href="<?php echo esc_url($link); ?>"><?php echo esc_html($link); ?></a></p>
<p><?php _e('Algunos programas de correo no permiten links directos, si te da problemas copia el link y pegalo en tu navegador.', Campus::TEXT_DOMAIN); ?></p>
<p><a href="<?php echo esc_url($siteurl); ?>"><?php echo esc_html($sitename); ?></a></p>
<p><?php echo esc_html($sitedescription); ?></p>
