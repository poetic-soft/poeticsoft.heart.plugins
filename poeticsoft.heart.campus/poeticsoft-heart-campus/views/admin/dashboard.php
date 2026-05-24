<?php

/**
 * Dashboard Template.
 * 
 * @var \Poeticsoft\Heart\Admin\AbstractPage $page
 * @var string $welcome_message
 * @var array  $status_data
 */
?>
<p><?php echo esc_html($welcome_message); ?></p>

<div class="card" style="max-width: 100%;">
    <h2><?php esc_html_e('System Status', \Poeticsoft\Heart\Campus::TEXT_DOMAIN); ?></h2>
    <p><?php echo esc_html($status_data['message']); ?></p>
</div>

<div style="margin-top: 20px;">
    <form method="post" action="">
        <input type="hidden" name="action" value="refresh_status">
        <?php $page->nonce_field(); ?>
        <?php submit_button(__('Refresh System Status', \Poeticsoft\Heart\Campus::TEXT_DOMAIN), 'secondary'); ?>
    </form>
</div>