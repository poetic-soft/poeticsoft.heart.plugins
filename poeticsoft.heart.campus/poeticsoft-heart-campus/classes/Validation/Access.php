<?php

namespace Poeticsoft\Heart\Validation;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Utils\Utils;
use Poeticsoft\Heart\View\View;

class Access
{
    public function __construct()
    {
        add_action(
            'template_redirect',
            [$this, 'check_access']
        );
    }

    public function check_access()
    {
        global $post;

        if (
            $post
            &&
            isset($_GET['action'])
            &&
            $_GET['action'] == 'logout'
        ) {
            $is_wp_logged_in = is_user_logged_in();
            unset($_COOKIE['campus_user_useremail']);
            setcookie('campus_user_useremail', '', time() - 3600, '/', COOKIE_DOMAIN, is_ssl(), true);

            if ($is_wp_logged_in) {
                wp_logout();
            }

            wp_safe_redirect(get_permalink($post->ID));
            exit;
        }

        if (isset($_GET['access'])) {
            $token = $_GET['access'];
            $trasient_key = Campus::PREFIX . 'access_link_' . $token;
            $transient = get_transient($trasient_key);

            if (!$transient) {
                wp_safe_redirect(home_url('/'));
            } else {
                $access_data = json_decode($transient);
                $email = $access_data->email;
                $url = $access_data->url;
                unset($_COOKIE['campus_user_useremail']);
                setcookie('campus_user_useremail', '', time() - 3600, '/', COOKIE_DOMAIN, is_ssl(), true);

                setcookie(
                    'campus_user_useremail',
                    $email,
                    0,
                    '/',
                    COOKIE_DOMAIN,
                    is_ssl(),
                    true
                );

                wp_safe_redirect($url);
            }
        }
    }

    public static function send_magick_link($email, $url)
    {
        $magick_link_duration_option_name = Campus::PREFIX . 'magick_link_duration';
        $magick_link_duration = get_option($magick_link_duration_option_name);
        $expire_time = $magick_link_duration * DAY_IN_SECONDS;
        $link_data = [
            'email' => $email,
            'url' => $url,
            'expire_time' => $expire_time
        ];
        $encoded_link_data = json_encode($link_data);
        $token = wp_generate_password(32, false);
        $trasient_key = Campus::PREFIX . 'access_link_' . $token;

        set_transient(
            $trasient_key,
            $encoded_link_data,
            $expire_time
        );

        $link = add_query_arg('access', $token, home_url('/'));
        $sitename = get_bloginfo('name');
        $sitedescription = get_bloginfo('description');
        $siteurl = get_bloginfo('url');

        $message = Campus::get(View::class)->render('emails/magick-link', [
            'link'            => $link,
            'sitename'        => $sitename,
            'sitedescription' => $sitedescription,
            'siteurl'         => $siteurl,
        ], false);

        $headers = ['Content-Type: text/html; charset=UTF-8'];

        $sent = wp_mail(
            $email,
            '[' . $sitename . '] Link de acceso',
            $message,
            $headers
        );

        return $link;
    }

    public function all_user_access_posts(
        $max_count,
        $order_by_date = 'ASC',
        $status = 'publish',
    ) {
        $email = $this->validate_email();

        if (!$email) {
            return [];
        }

        global $wpdb;
        $table_name = $wpdb->prefix . Campus::PREFIX . 'access';
        $user_page_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT post_id FROM {$table_name} WHERE user_mail = %s",
                $email
            )
        );

        if (!empty($user_page_ids)) {
            $campus_root_id = $this->get_campus_root_id();

            $all_pages = get_pages(
                [
                    'post_type'   => 'page',
                    'post_status' => $status,
                    'child_of'    => $campus_root_id
                ]
            );

            $filtered_pages = array();

            foreach ($all_pages as $page) {
                if (
                    in_array($page->ID, $user_page_ids)
                    ||
                    array_intersect($page->ancestors, $user_page_ids)
                ) {
                    $filtered_pages[] = $page;
                }
            }

            usort(
                $filtered_pages,
                function ($a, $b) {
                    return strtotime($b->post_date) - strtotime($a->post_date);
                }
            );

            $limit = ($max_count > 0) ? $max_count : null;
            $posts_ordered = array_slice($filtered_pages, 0, $limit);

            return $posts_ordered;
        }

        return [];
    }

    public function get_campus_root_id()
    {
        return Utils::get_campus_root_id();
    }

    public function get_allow_admin()
    {
        return Utils::get_allow_admin();
    }

    public function post_is_in_campus($post_id)
    {
        return Utils::post_is_in_campus($post_id);
    }

    public function logged_user_mail()
    {
        $user_id = get_current_user_id();

        if ($user_id) {
            $user_info = get_userdata($user_id);
            if ($user_info) {
                $email = $user_info->user_email;
                unset($_COOKIE['campus_user_useremail']);
                setcookie('campus_user_useremail', '', time() - 3600, '/', COOKIE_DOMAIN, is_ssl(), true);

                setcookie(
                    'campus_user_useremail',
                    $email,
                    0,
                    '/',
                    COOKIE_DOMAIN,
                    is_ssl(),
                    true
                );

                return $email;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function validate_email()
    {
        $logged_user_email = $this->logged_user_mail();

        if ($logged_user_email) {
            return $logged_user_email;
        } else {
            if (isset($_COOKIE['campus_user_useremail'])) {
                return $_COOKIE['campus_user_useremail'];
            } else {
                return false;
            }
        }
    }

    public function can_access_cause_not_in_campus($post_id)
    {
        return !$this->post_is_in_campus($post_id);
    }

    public function can_access_cause_is_admin()
    {
        $current_user = wp_get_current_user();
        $allow_admin = $this->get_allow_admin();
        $can_access = false;

        if (
            in_array(
                'administrator',
                (array) $current_user->roles
            )
            &&
            $allow_admin
        ) {
            $can_access = true;
        }

        return $can_access;
    }

    public function can_access_cause_is_open($post_id)
    {
        if ($post_id) {
            $access = get_post_meta(
                $post_id,
                Campus::PREFIX . 'access',
                true
            )
                ?: 'restringida';

            return $access != 'restringida';
        }

        return false;
    }

    public function can_access_by_post_access($post_id)
    {
        $valid_user_mail = $this->validate_email();
        if (!$valid_user_mail) {
            return false;
        }

        $cache_enabled = (bool) get_option(Campus::PREFIX . 'block_cache_enabled', true);
        $cache_key = $cache_enabled ? 'poeticsoft_heart_campus_acc_' . md5($valid_user_mail . '_' . $post_id) : '';

        if ($cache_key) {
            $cached = get_transient($cache_key);
            if (false !== $cached) {
                return $cached === '1';
            }
        }

        global $wpdb;

        if ($post_id) {
            $ancestor_ids = get_post_ancestors($post_id);
            array_unshift($ancestor_ids, $post_id);
            $table_name = $wpdb->prefix . Campus::PREFIX . 'access';
            $placeholders = implode(',', array_fill(0, count($ancestor_ids), '%d'));
            $query = $wpdb->prepare(
                "SELECT COUNT(*)
                FROM {$table_name}
                WHERE user_mail = %s
                AND post_id IN ({$placeholders})",
                array_merge([$valid_user_mail], $ancestor_ids)
            );
            $exists = $wpdb->get_var($query);
            $result = $exists > 0;

            if ($cache_key) {
                set_transient($cache_key, $result ? '1' : '0', 0);
            }

            return $result;
        } else {
            return false;
        }
    }

    public function can_access_cause_child_accessible($post_id)
    {
        $valid_user_mail = $this->validate_email();
        if (!$valid_user_mail) {
            return false;
        }

        $cache_enabled = (bool) get_option(Campus::PREFIX . 'block_cache_enabled', true);
        $cache_key = $cache_enabled ? 'poeticsoft_heart_campus_child_acc_' . md5($valid_user_mail . '_' . $post_id) : '';

        if ($cache_key) {
            $cached = get_transient($cache_key);
            if (false !== $cached) {
                return $cached === '1';
            }
        }

        global $wpdb;

        $descendants = get_pages(array(
            'child_of' => $post_id,
            'post_type' => 'page'
        ));
        $descendants_ids = wp_list_pluck($descendants, 'ID');

        if (!empty($descendants_ids)) {
            $post_meta_table_name = $wpdb->prefix . 'postmeta';
            $access_table_name = $wpdb->prefix . Campus::PREFIX . 'access';
            $meta_key = Campus::PREFIX . 'access';
            $descendants_ids_str = implode(',', array_map('intval', $descendants_ids));
            $sql = $wpdb->prepare("
                SELECT post_id AS id FROM {$post_meta_table_name}
                WHERE 
                meta_key = %s 
                AND 
                meta_value = 'abierta'
                AND 
                post_id IN ($descendants_ids_str)  

                UNION 

                SELECT post_id AS id FROM {$access_table_name} 
                WHERE 
                user_mail = %s 
                AND 
                post_id IN ($descendants_ids_str)
            ", $meta_key, $valid_user_mail);

            $descendants_visibles = $wpdb->get_results($sql);
            $result = count($descendants_visibles) > 0;

            if ($cache_key) {
                set_transient($cache_key, $result ? '1' : '0', 0);
            }

            return $result;
        }

        if ($cache_key) {
            set_transient($cache_key, '0', 0);
        }

        return false;
    }

    public function can_access($post_id)
    {
        if ($this->can_access_cause_not_in_campus($post_id)) {
            return true;
        }

        if ($this->can_access_cause_is_admin()) {
            return true;
        }

        if ($this->can_access_cause_is_open($post_id)) {
            return true;
        }

        if ($this->can_access_by_post_access($post_id)) {
            return true;
        }

        return false;
    }
}
