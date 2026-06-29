<?php

use Poeticsoft\Heart\Base\Base;

?>
<p><?php echo esc_html($welcome_message); ?></p>

<div class="card" style="max-width: 100%;">
	<h2><?php esc_html_e('System Status', Base::TEXT_DOMAIN); ?></h2>
	<p><?php echo esc_html($status_data['message']); ?></p>
</div>

<div style="margin-top: 20px;">
	<form method="post" action="">
		<input type="hidden" name="action" value="refresh_status">
		<?php $page->nonce_field(); ?>
		<?php submit_button(__('Refresh System Status', Base::TEXT_DOMAIN), 'secondary'); ?>
	</form>
</div>

