<?php

/**
 * Frontend Admin View Banner View.
 *
 * @package Poeticsoft\Heart
 */

use Poeticsoft\Heart\Campus;

if (! defined('ABSPATH')) {
    exit;
}
?>
<div class="ViewAsAdmin">
    <?php _e('Vista de administrador', Campus::TEXT_DOMAIN); ?> 
    (<a href="/wp-login.php?action=logout"><?php _e('SALIR', Campus::TEXT_DOMAIN); ?></a>) 
</div>
