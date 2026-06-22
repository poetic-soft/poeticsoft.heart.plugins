<?php
/**
 * Admin Notice Template.
 * 
 * @var string $type    Notice type (success, error, warning, info).
 * @var string $message Notice message.
 */
?>
<div class="notice notice-<?php echo esc_attr( $type ); ?> is-dismissible">
        <p><?php echo esc_html( $message ); ?></p>
</div>
