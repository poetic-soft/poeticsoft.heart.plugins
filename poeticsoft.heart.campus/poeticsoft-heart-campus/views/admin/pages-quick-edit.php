<?php

/**
 * Admin Pages Quick Edit View.
 *
 * @package Poeticsoft\Heart
 */

use Poeticsoft\Heart\Campus;

if (! defined('ABSPATH')) {
    exit;
}
?>
<fieldset class="inline-edit-col-right <?php echo esc_attr($meta_class); ?>">
    <div class="inline-edit-col">
        <label class="inline-edit-group">
            <span class="title"><?php _e('Acceso', Campus::TEXT_DOMAIN); ?></span>
            <select name="<?php echo esc_attr($meta_key); ?>" class="<?php echo esc_attr($meta_class); ?>">
                <?php if ($is_bulk) : ?>
                    <option value=""><?php _e('— Sin cambios —', Campus::TEXT_DOMAIN); ?></option>
                <?php endif; ?>
                <option value="abierta"><?php _e('Abierta', Campus::TEXT_DOMAIN); ?></option>
                <option value="restringida"><?php _e('Restringida', Campus::TEXT_DOMAIN); ?></option>
            </select>
        </label>
    </div>
</fieldset>
