<?php
/**
 * Generic Admin Notice Template.
 *
 * @var string $message
 * @var string $type (success, error, warning, info)
 */
?>
<div class="notice notice-<?php echo esc_attr($type); ?> is-dismissible">
    <p><?php echo esc_html($message); ?></p>
</div>
