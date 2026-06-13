<?php

/**
 * Frontend Post Content Access Restriction View.
 *
 * @package Poeticsoft\Heart
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<?php if ($valid_user_mail) : ?>
    <div class="wp-block-poeticsoft-heart-campus-postcontent" data-email="<?php echo esc_attr($valid_user_mail); ?>" data-post_id="<?php echo esc_attr($post_id); ?>">
        <div class="Forms CantAccess">
            <div class="AdviceText">
                <?php echo $restricted_visible_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="wp-block-poeticsoft-heart-campus-postcontent">
        <div class="Forms Identify">
            <div class="AdviceText">
                <?php echo $restricted_visible_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </div>
    </div>
<?php endif; ?>
