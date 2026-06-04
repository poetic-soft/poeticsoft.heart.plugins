<?php

namespace Poeticsoft\Heart\Validation;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Utils\Utils;

/**
 * Validation Accesos a Páginas del campus.
 */

class Access {

	/**
	 * Constructor.
	 */
	public function __construct() {
        
		add_action('template_redirect', [$this, 'check_logout']);
	}
    
    public function check_logout() {

        global $post;

        if (
            $post
            &&
            isset($_GET['action'])
            &&
            $_GET['action'] == 'logout'
        ) {

            unset($_COOKIE['useremail']);
            unset($_COOKIE['codeconfirmed']);
            setcookie('useremail', '', time() - 3600, '/');
            setcookie('codeconfirmed', '', time() - 3600, '/');
            
            if($this->logged_user_mail()) {
                
                wp_logout();
            }
            
            wp_safe_redirect(get_permalink($post->ID));
            
            exit;
        }
    }
    
    public function get_campus_root_id() {
        return Utils::get_campus_root_id();
    }
    
    public function get_allow_admin() {
        return Utils::get_allow_admin();
    }
    
    public function post_is_in_campus($post_id) {
        return Utils::post_is_in_campus($post_id);
    }
    
    public function logged_user_mail() {   
    
        $user_id = get_current_user_id();
        
        if($user_id) {
        
            $user_info = get_userdata($user_id);
            if ($user_info) {
                
                $email = $user_info->user_email; 

                unset($_COOKIE['useremail']);
                unset($_COOKIE['codeconfirmed']);
                setcookie('useremail', '', time() - 3600, '/');
                setcookie('codeconfirmed', '', time() - 3600, '/');
                
                setcookie(
                    'useremail',
                    $email, 
                    0,
                    '/',
                    COOKIE_DOMAIN,
                    is_ssl(),
                    true
                );

                setcookie(
                    'codeconfirmed',
                    'no',
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
  
    public function validate_email() {
    
        $logged_user_email = $this->logged_user_mail();
    
        if($logged_user_email) {
        
            return $logged_user_email;
        
        } else {
        
            if(
                isset($_COOKIE['useremail'])
                &&
                isset($_COOKIE['codeconfirmed'])
                &&
                $_COOKIE['codeconfirmed'] == 'yes'
            ) { 

                return $_COOKIE['useremail'];

            } else {

                return false;
            }  
        }
    } 

    public function can_access_cause_not_in_campus($post_id) { 

        return !$this->post_is_in_campus($post_id);
    } 
  
    public function can_access_cause_is_admin() { 

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

    function can_access_cause_is_open($post_id) {

        if($post_id) {

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

    function can_access_by_post_access($post_id) {
      
        $valid_user_mail = $this->validate_email();
        if(!$valid_user_mail) {
        
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

        if($post_id) {
            
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

    public function can_access_cause_child_accessible($post_id) {

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

        if(!empty($descendants_ids)) {  
            
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
  
    public function can_access($post_id) { 
     
        if($this->can_access_cause_not_in_campus($post_id)) {

            return true;    
        }

        if($this->can_access_cause_is_admin()) { 
        
            return true;     
        }

        if($this->can_access_cause_is_open($post_id)) {
        
            return true;     
        }

        if($this->can_access_by_post_access($post_id)) { 
        
            return true;     
        }

        return false;
    } 
}
