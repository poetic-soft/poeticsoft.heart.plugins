<?php
use Poeticsoft\Heart\Heart;
?>
<div class="card" style="margin-top: 20px; max-width: 800px; padding: 20px; background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
	<h2><?php esc_html_e('Send Test Email', Heart::TEXT_DOMAIN); ?></h2>
	<p><?php esc_html_e('Fill in an email address below to send a test email and verify your custom SMTP configuration.', Heart::TEXT_DOMAIN); ?></p>
	
	<form method="post" action="">
		<?php $page->nonce_field(); ?>
		<input type="hidden" name="action" value="send_test_email" />
		
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row">
						<label for="test_email_to"><?php esc_html_e('Recipient Email', Heart::TEXT_DOMAIN); ?></label>
					</th>
					<td>
						<input type="email" id="test_email_to" name="test_email_to" class="regular-text" required placeholder="recipient@example.com" />
						<p class="description"><?php esc_html_e('The email address to send the test message to.', Heart::TEXT_DOMAIN); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<?php submit_button(__('Send Test Email', Heart::TEXT_DOMAIN), 'secondary'); ?>
	</form>
</div>
