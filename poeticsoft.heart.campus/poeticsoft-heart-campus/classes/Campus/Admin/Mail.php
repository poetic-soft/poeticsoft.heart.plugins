<?php

namespace Poeticsoft\Heart\Campus\Admin;

use Poeticsoft\Heart\Campus\Campus;
use Poeticsoft\Heart\Campus\Utils\Utils;

class Mail
{
    public function init()
    {
        add_action(
            'phpmailer_init',
            [$this, 'phpmailer_init']
        );

        add_filter(
            'wp_mail_from',
            [$this, 'mail_from']
        );

        add_filter(
            'wp_mail_from_name',
            [$this, 'mail_from_name']
        );

        add_action(
            'wp_mail_failed',
            [$this, 'mail_failed']
        );
    }

    public function phpmailer_init($phpmailer)
    {
        $smtp_use = get_option(Campus::PREFIX . 'smtp_use');

        if (!$smtp_use) {
            return;
        }

        $smtp_host       = get_option(Campus::PREFIX . 'smtp_host');
        $smtp_port       = get_option(Campus::PREFIX . 'smtp_port');
        $smtp_smtpsecure = get_option(Campus::PREFIX . 'smtp_secure');
        $smtp_username   = get_option(Campus::PREFIX . 'smtp_user');
        $smtp_password   = get_option(Campus::PREFIX . 'smtp_pass');
        $smtp_from       = get_option(Campus::PREFIX . 'smtp_from_email');
        $smtp_fromname   = get_option(Campus::PREFIX . 'smtp_from_name');
        $phpmailer->isSMTP();

        $phpmailer->Debugoutput = function ($str, $level) {
            Utils::log("SMTP debug [$level]: " . trim($str));
        };

        $phpmailer->SMTPAuth = !empty($smtp_username);
        $phpmailer->SMTPSecure = ($smtp_smtpsecure === 'none') ? '' : $smtp_smtpsecure;
        $phpmailer->Host = $smtp_host;
        $phpmailer->Port = $smtp_port;
        $phpmailer->Username = $smtp_username;
        $phpmailer->Password = $smtp_password;

        if ($smtp_from) {
            $phpmailer->From = $smtp_from;
        }

        if ($smtp_fromname) {
            $phpmailer->FromName = $smtp_fromname;
        }

        $phpmailer->isHTML(true);
    }

    public function mail_from($from)
    {
        $smtp_use = get_option(Campus::PREFIX . 'smtp_use');
        if (!$smtp_use) {
            return $from;
        }

        $smtp_from_email = get_option(Campus::PREFIX . 'smtp_from_email');

        return $smtp_from_email ? $smtp_from_email : $from;
    }

    public function mail_from_name($from_name)
    {
        $smtp_use = get_option(Campus::PREFIX . 'smtp_use');
        if (!$smtp_use) {
            return $from_name;
        }

        $smtp_from_name = get_option(Campus::PREFIX . 'smtp_from_name');

        return $smtp_from_name ? $smtp_from_name : $from_name;
    }

    public function mail_failed($wp_error)
    {
        Utils::log('---------------------------------------------------------------');
        Utils::log('wp_mail failed error details:');
        Utils::log('---------------------------------------------------------------');
        Utils::log($wp_error);
        Utils::log('---------------------------------------------------------------');
        Utils::log('host: ' .       get_option(Campus::PREFIX . 'smtp_host'));
        Utils::log('port: ' .       get_option(Campus::PREFIX . 'smtp_port'));
        Utils::log('smtpsecure: ' . get_option(Campus::PREFIX . 'smtp_secure'));
        Utils::log('username: ' .   get_option(Campus::PREFIX . 'smtp_user'));
        Utils::log('password: ' .   get_option(Campus::PREFIX . 'smtp_pass'));
        Utils::log('from: ' .       get_option(Campus::PREFIX . 'smtp_from_email'));
        Utils::log('fromname: ' .   get_option(Campus::PREFIX . 'smtp_from_name'));
        Utils::log('---------------------------------------------------------------');
    }
}
