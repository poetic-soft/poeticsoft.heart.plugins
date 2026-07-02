<?php

use Poeticsoft\Heart\Heart;

?>

<div>
    <hr >
	<form 
        method="post" 
        action=""
        style="
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
        "
    >
		<?php $page->nonce_field(); ?>
		<input 
            type="hidden" 
            name="action" 
            value="send_mail"            
        />
        <input 
            type="email"
            name="email"
            value="<?php echo $smtp_test_recipient ?>"
        />
		<?php submit_button(
            __('Enviar mail de test', Heart::TEXT_DOMAIN), 
            'secondary',
            '',
            false
        ); ?>
	</form>
</div>

