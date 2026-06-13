<?php

/**
 * Dashboard Template.
 *
 * @var \Poeticsoft\Heart\Admin\Page $page
 * @var string $sorted_list
 */

    $access_table = '';
    $index = 1;
foreach ($sorted_list as $mail => $titles) {
    $titles_table = '<div class="posts">';
    foreach ($titles as $title) {
        $titles_table .= '<div class="post">' .
            $title .
        '</div>';
    }
    $titles_table .= '</div>';

    $access_table .= '<div class="user">
            <div class="index-mail">
                <div class="index">' .
                $index .
            '</div>
                <div class="mail">' .
                $mail .
            '</div>
            </div>' .
        $titles_table .
    '</div>';

    $index++;
}
?>

<div class="tools">
    <form method="post" action="">
        <input type="hidden" name="action" value="refresh_access">
        <?php $page->nonce_field(); ?>
        <?php submit_button(__('Recargar accesos', \Poeticsoft\Heart\Campus::TEXT_DOMAIN), 'secondary'); ?>
    </form>
</div>

<div class="stats">
    <div class="stat users">
        <div class="text"><?php _e('Humanos', \Poeticsoft\Heart\Campus::TEXT_DOMAIN); ?></div> 
        <div class="value"><?php echo $sorted_list->count(); ?></div>      
    </div>
</div>

<div class="access">
    <table>
    <?php echo $access_table; ?>        
    </table>
</div>