<?php

/**
 * Cache Management Card View.
 *
 * @package Poeticsoft\Heart
 */

use Poeticsoft\Heart\Campus;

if (! defined('ABSPATH')) {
    exit;
}
?>
<div class="card">
    <h2><?php _e('Gestión de Caché', Campus::TEXT_DOMAIN); ?></h2>
    <p><?php _e('Usa este botón para borrar manualmente todo el contenido de bloques guardado en caché.', Campus::TEXT_DOMAIN); ?></p>
    <p>
        <a href="<?php echo esc_url($clear_cache_url); ?>" class="button button-secondary">
            <?php _e('Borrar todas las cachés de bloques', Campus::TEXT_DOMAIN); ?>
        </a>
    </p>
</div>
