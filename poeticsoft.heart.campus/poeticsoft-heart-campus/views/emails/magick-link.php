<?php

/**
 * Magick Link Email View.
 *
 * @package Poeticsoft\Heart\Campus
 */

use Poeticsoft\Heart\Campus\Campus;

if (! defined('ABSPATH')) {
    exit;
}

// Get custom site logo
$logo_id = get_theme_mod('custom_logo');
$logo_url = '';
if ($logo_id) {
    $logo_data = wp_get_attachment_image_src($logo_id, 'full');
    if ($logo_data) {
        $logo_url = $logo_data[0];
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($sitename); ?></title>
</head>

<body style="
    margin: 0;
    padding: 0;
    background-color: #f8fafc;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
">
    <table
        border="0"
        cellpadding="0"
        cellspacing="0"
        width="100%"
        style="background-color: #f8fafc; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table
                    border="0"
                    cellpadding="0"
                    cellspacing="0"
                    width="100%"
                    style="
                        max-width: 580px;
                        background-color: #ffffff;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                        border: 1px solid #e2e8f0;
                    ">

                    <!-- Header with Logo/Sitename -->
                    <tr>
                        <td align="center" style="padding: 40px 40px 20px 40px;">
                            <?php if ($logo_url) : ?>
                                <img
                                    src="<?php echo esc_url($logo_url); ?>"
                                    alt="<?php echo esc_attr($sitename); ?>"
                                    style="max-height: 60px; width: auto; display: block; border: 0;" />
                            <?php else : ?>
                                <span style="
                                    font-size: 24px;
                                    font-weight: 700;
                                    color: #0f172a;
                                    letter-spacing: -0.025em;
                                "><?php echo esc_html($sitename); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Main Content Block -->
                    <tr>
                        <td style="padding: 0 40px 40px 40px; text-align: center;">
                            <h1 style="font-size: 20px; font-weight: 600; color: #0f172a; margin: 0 0 12px 0;">
                                <?php _e('Enlace de acceso', Campus::TEXT_DOMAIN); ?>
                            </h1>

                            <p style="font-size: 15px; line-height: 1.6; color: #334155; margin: 0 0 24px 0;">
                                <?php _e('Usa este link para acceder:', Campus::TEXT_DOMAIN); ?>
                            </p>

                            <!-- Button Container -->
                            <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 32px auto;">
                                <tr>
                                    <td align="center" style="border-radius: 8px; background-color: #0284c7;">
                                        <a
                                            href="<?php echo esc_url($link); ?>"
                                            target="_blank"
                                            style="
                                                display: inline-block;
                                                padding: 14px 32px;
                                                font-size: 15px;
                                                font-weight: 600;
                                                color: #ffffff;
                                                text-decoration: none;
                                                border-radius: 8px;
                                                background-color: #0284c7;
                                                border: 1px solid #0284c7;
                                                transition: background-color 0.2s ease;
                                            "><?php _e('ENTRAR', Campus::TEXT_DOMAIN); ?></a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Fallback / Raw Link Info -->
                            <div style="
                                background-color: #f1f5f9;
                                border-radius: 8px;
                                padding: 16px;
                                border: 1px solid #e2e8f0;
                                text-align: left;
                            ">
                                <p style="font-size: 13px; line-height: 1.5; color: #475569; margin: 0 0 10px 0;">
                                    <?php
                                    _e(
                                        'Algunos programas de correo no permiten links directos, ' .
                                            'si te da problemas copia el link y pegalo en tu navegador.',
                                        Campus::TEXT_DOMAIN
                                    );
                                    ?>
                                </p>
                                <p style="
                                    font-size: 12px;
                                    word-break: break-all;
                                    margin: 0;
                                    font-family: SFMono-Regular, Consolas, 'Liberation Mono', Menlo, Courier, monospace;
                                ">
                                    <a
                                        href="<?php echo esc_url($link); ?>"
                                        style="color: #0284c7; text-decoration: none; font-weight: 500;"><?php echo esc_html($link); ?></a>
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Footer with Site URL / description -->
                <table
                    border="0"
                    cellpadding="0"
                    cellspacing="0"
                    width="100%"
                    style="max-width: 580px; margin-top: 24px; text-align: center;">
                    <tr>
                        <td style="padding: 0 20px; font-size: 12px; line-height: 1.5; color: #64748b;">
                            <p style="margin: 0 0 4px 0;">
                                <a
                                    href="<?php echo esc_url($siteurl); ?>"
                                    style="color: #475569; text-decoration: none; font-weight: 600;"><?php echo esc_html($sitename); ?></a>
                            </p>
                            <?php if ($sitedescription) : ?>
                                <p style="margin: 0; font-size: 11px; color: #94a3b8;">
                                    <?php echo esc_html($sitedescription); ?>
                                </p>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>

</html>