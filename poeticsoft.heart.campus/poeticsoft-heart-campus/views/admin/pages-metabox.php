<?php

/**
 * Admin Pages Metabox View.
 *
 * @package Poeticsoft\Heart\Campus
 */

use Poeticsoft\Heart\Campus\Campus;

if (! defined('ABSPATH')) {
    exit;
}
?>
<div class="components-base-control">
    <label class="components-base-control__label" style="display:block; margin-bottom: 5px; font-weight:600;">
        <?php _e('Acceso', Campus::TEXT_DOMAIN); ?>
    </label>
    <select name="<?php echo esc_attr($meta_key); ?>" class="<?php echo esc_attr($meta_class); ?>" style="width:100%; height:30px; box-sizing:border-box;">
        <option value="abierta" <?php selected($current_value, 'abierta'); ?>>
            <?php _e('Abierta', Campus::TEXT_DOMAIN); ?>
        </option>
        <option value="restringida" <?php selected($current_value, 'restringida'); ?>>
            <?php _e('Restringida', Campus::TEXT_DOMAIN); ?>
        </option>
    </select>
</div>
