<?php

namespace Poeticsoft\Heart\Database;

use Poeticsoft\Heart\Campus;

/**
 * Database Updater, Use Selected Method for Update Acess Table.
 */
class Updater
{   
    
    /**
     * Syncro posts & access.
     * 
     * @return string
     */   
    
    public function get_formated_access_data()
    {   
        
        $campus_root_id_option_name = sprintf('%sroot_post_id', Campus::PREFIX);
        $campus_root_id = get_option($campus_root_id_option_name);
        
        if(!$campus_root_id) {
            
            return;
        }
        
        $campus_pages = get_pages([
            'child_of'    => $campus_root_id,
            'post_type'   => 'page',
            'post_status' => [
                'publish',
                'draft',
                'pending',
                'private',
            ],
        ]);
    
        $campus_root = get_post($campus_root_id);
        $campus_pages_ide_title = [];        
        $campus_pages_ide_title[$campus_root_id] = get_the_title($campus_root_id);
        
        /**
         * DEBUG WITH LOCAL POST!
         */
        $debug_ids = [];
        foreach ($campus_pages as $page) {            
            
            $campus_pages_ide_title[$page->ID] = get_the_title($page->ID);
            $debug_ids[] = $page->ID;
        }
        
        $resultado = [];        

        global $wpdb;
        
        $table_name = $wpdb->prefix . Campus::PREFIX . 'access';
        $actual_data = $wpdb->get_results( "SELECT * FROM $table_name" );
        
        foreach($actual_data as $access) {
            
            $post_id = $access->post_id;
            
            /**
             * DEBUG WITH LOCAL POST!
             * Si testeamos con datos externos los ids no cuadran! ponemos unos nuestros al tun tun
             */
            $debug_post_id = $post_id ? $post_id : $debug_ids[rand(0, count($debug_ids) - 1)];
            
            $campus_page_title = $campus_pages_ide_title[$debug_post_id];
            
            $user_mail = $access->user_mail;
            
            if(isset($resultado[$user_mail])) {
                
                $resultado[$user_mail][] = $campus_page_title;
                
            } else {               
                        
                $resultado[$user_mail] = [$campus_page_title];
            }             
        }

        return $resultado;
    }
    
    /**
     * Refresh database.
     * 
     * @return string
     */   
    
    public function refresh_access_data() { 

        global $wpdb;
        
        $access_data = $this->get_access_data();
        
        $table_name = $wpdb->prefix . Campus::PREFIX . 'access';
        $wpdb->query("TRUNCATE TABLE $table_name");
        foreach($access_data as $access) {

            $wpdb->insert(
                $table_name,
                $access,
                [
                    '%s', // user_mail
                    '%d', // post_id
                ]
            );
        }
        
        return $access_data;
    }   
    
    /**
     * Get actual data.
     * 
     * @return string
     */   
    
    public function get_access_data()
    {   
        $access_by_option_name = CAMPUS::PREFIX . 'access_by';
        $access_by = get_option($access_by_option_name);
        
        $data = [];
        
        switch ($access_by) {
            
            case 'directus':
                
                $data = $this->get_directus_access_data();
                
                break;
            
            case 'mailrelay':
                
                break;
                
        }
        
        return $data;
    }
    
    /**
     * Get actual directus data.
     * 
     * @return string
     */
    
    public function get_directus_access_data()
    {
        
        $directus_access_url_option_name = CAMPUS::PREFIX . 'access_url';
        $directus_access_url = get_option($directus_access_url_option_name);
        
        $directus_access_token_option_name = CAMPUS::PREFIX . 'access_token';
        $directus_access_token = get_option($directus_access_token_option_name);   
        
        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $directus_access_token,
                'Content-Type'  => 'application/json', // Opcional, pero recomendado
            ],
            'timeout' => 30, // Tiempo de espera en segundos
        ];
        
        $response = wp_remote_get(
            $directus_access_url,
            $args
        );
        
        $data = [];
        
        if (!is_wp_error($response)) {
                
            $http_code = wp_remote_retrieve_response_code($response);
            if ($http_code !== 200) {
                
                $data = [];
                
            } else {
                
                $body = wp_remote_retrieve_body($response);
                $directus_data = json_decode($body);
                
                foreach($directus_data->data as $row) {

                    $emailvalue = sanitize_email(trim($row->humano_id->correo)); 
                    $postidsvalue = trim($row->wp_post_ids);
                    $postids = $postidsvalue == '' ?
                    []
                    :
                    explode(' ', $postidsvalue);
                    $postids = array_map(
                        function($postid) { return trim($postid); },
                        $postids
                    );

                    if(count($postids)) {

                        foreach($postids as $postid) {

                            $post = get_post($postid);
                            $postid = $post ? $postid : 'no';
                            $access = [
                                'user_mail' => $emailvalue,
                                'post_id' => $postid
                            ];

                            $data[] = $access;
                        }

                    } else {

                        $access = [
                            'user_mail' => $emailvalue,
                            'post_id' => 0
                        ];
                        
                        $data[] = $access;
                    }     
                }
            }
        }
            
        return $data;
    }
}
