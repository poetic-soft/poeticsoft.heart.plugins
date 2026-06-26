<?php

/**
 * Dashboard Template.
 *
 * @var \Poeticsoft\Heart\Campus\Admin\Page $page
 * @var string $sorted_list
 */

use Poeticsoft\Heart\Campus\Campus;

$access_table = '';
$index = 1;
foreach ($sorted_list as $mail => $datas) {
    $subscriptions_table = '<div class="posts">';
    foreach ($datas as $data) {

        $subscriptions_table .= '<div class="post">
            <span class="pageid">' .
            $data['page_id'] .
            '</span>
            <span class="pagepath">' .
            $data['page_path'] .
            '</span>
        </div>';
    }
    $subscriptions_table .= '</div>';

    $access_table .= '<div class="user">
        <div class="index-mail">
            <div class="index">' .
        $index .
        '</div>
            <div class="mail">' .
        $mail .
        '</div>
        </div>' .
        $subscriptions_table .
        '</div>';

    $index++;
}
?>

<div class="tools">
    <form method="post" action="">
        <input type="hidden" name="action" value="refresh_access">
        <?php $page->nonce_field(); ?>
        <?php submit_button(__('Recargar accesos', Campus::TEXT_DOMAIN), 'secondary'); ?>
    </form>
</div>

<div class="stats">
    <div class="stat users">
        <div class="text"><?php _e('Humanos', Campus::TEXT_DOMAIN); ?></div>
        <div class="value"><?php echo $sorted_list->count(); ?></div>
    </div>
</div>

<div class="access">
    <table>
        <?php echo $access_table; ?>
    </table>
</div>