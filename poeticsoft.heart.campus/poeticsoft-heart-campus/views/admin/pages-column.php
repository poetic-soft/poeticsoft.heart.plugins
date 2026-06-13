<?php

/**
 * Admin Pages Column View.
 *
 * @package Poeticsoft\Heart
 */

use Poeticsoft\Heart\Campus;

if (! defined('ABSPATH')) {
    exit;
}
?>
<div class="<?php echo esc_attr(Campus::PLUGIN_SLUG . '-access-column'); ?>">
    <strong><?php echo esc_html($post_id); ?></strong>
    - 
    <?php echo esc_html('abierta' === $current_value ? __('Abierta', Campus::TEXT_DOMAIN) : __('Restringida', Campus::TEXT_DOMAIN)); ?>
</div>
