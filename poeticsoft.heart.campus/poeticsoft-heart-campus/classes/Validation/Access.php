<?php

namespace Poeticsoft\Heart\Validation;

use Poeticsoft\Heart\Campus;

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
    
    public function get_campus_root_id() {
        
        $campus_root_id_option_name = sprintf('%sroot_post_id', Campus::PREFIX);
        $campus_root_id = get_option($campus_root_id_option_name);
        
        return $campus_root_id ?? null;
    }
    
    public function get_allow_admin() {
        
        $allow_admin_option_name = sprintf('%sadmin_access', Campus::PREFIX);
        $allow_admin = get_option($allow_admin_option_name);
        
        return $$allow_admin ?? false;
    }
    
    public function post_is_in_campus($post_id) {

        if($post_id) {

            $campus_root_id = $this->get_campus_root_id();
            $ancestors = get_post_ancestors($post_id);

            if(
                in_array(intval($campus_root_id), $ancestors)
                ||
                $post_id == $campus_root_id
            ) {

                return true;
            }   
        }
        
        return false;
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

    public function can_access_causenotincampus($post_id) { 

        return !$this->post_is_in_campus($post_id);
    } 
  
    public function can_access_causeisadmin() { 

        $current_user = wp_get_current_user();
        $allow_admin = $this->get_allow_admin();
        $can_access = 'false';

        if (
            in_array(
                'administrator',
                (array) $current_user->roles
            )
            &&
            $allow_admin
        ) {

            $can_access = 'true';
        }  

        return $can_access === 'true';
    }

    function can_access_causeisfree($post_id) {

        $can_access = 'false';

        if($post_id) {

            $type = get_post_meta(
                $post_id,
                Campus::PREFIX . 'status',
                true
            );

            return $type;
        }

        return $can_access == 'true';
    }

    function can_access_bypostpaid($post_id) {
      
        $valid_user_mail = $this->validate_email();
        if(!$valid_user_mail) {
        
            return false;
        }
      
        $can_access = 'false';

        global $wpdb;

        if($post_id) {
            
            $ancestor_ids = $ancestors ?? get_post_ancestors($postid);
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
            
            return $exists > 0;

        } else {

            return false;
        }
    }

    public function can_access_causechildaccesible($post_id) {
    
        $can_access = 'false';

        global $wpdb;

        $descendants = get_pages(array(
            'child_of' => $post_id,
            'post_type' => 'page'
        ));
        $descendants_ids = wp_list_pluck($descendants, 'ID');

        if(!empty($descendants_ids)) {  
        
            $valid_user_mail = $this->validate_email();
            if (!$valid_user_mail) { 

                return false;
            }
            
            $post_meta_table_name = $wpdb->prefix . 'postmeta';
            $access_table_name = $wpdb->prefix . Campus::PREFIX . 'access';
            $meta_key = Campus::PREFIX . 'status';
            $descendants_ids = implode(',', $descendants_ids);
            $sql = "
                SELECT post_id AS id FROM {$post_meta_table_name}
                WHERE 
                meta_key = '{$meta_key}' 
                AND 
                meta_value = true
                AND 
                post_id IN ($descendants_ids)  

                UNION 

                SELECT post_id AS id FROM {$access_table_name} 
                WHERE 
                user_mail = '$valid_user_mail' 
                AND 
                post_id IN ($descendants_ids)
            ";
            $descendants_visibles = $wpdb->get_results($sql);
            
            if (count($descendants_visibles)) {
                
                $can_access = 'true';
            }
        }

        return $can_access == 'true'; 
    }
  
    public function can_access($post_id) { 
     
        $can_access = 'false';
    
        if($this->can_access_causenotincampus($post_id)) {

            $can_access = 'true';    
        }

        if($this->can_access_causeisadmin()) { 
        
            $can_access = 'true';     
        }

        if($this->can_access_causeisfree($post_id)) {
        
            $can_access = 'true';     
        }

        if($this->can_access_bypostpaid($post_id)) { 
        
            $can_access = 'true';     
        }

        return $can_access === 'true';
    } 
}
