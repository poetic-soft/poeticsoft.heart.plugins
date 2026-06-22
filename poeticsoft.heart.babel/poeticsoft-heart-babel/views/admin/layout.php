<?php
/**
 * Master Admin Page Layout.
 * 
 * @var \Poeticsoft\Heart\Babel\Admin\AbstractPage $page
 */

use Poeticsoft\Heart\Babel\Babel;
?>
<div class="wrap">
        <h1><?php echo esc_html( $page->get_page_title() ); ?></h1>
        <hr>
        <div class="<?php echo esc_attr( Babel::PLUGIN_SLUG ); ?>-page-content">
                <?php $page->render_content_internal(); ?>
        </div>
</div>
