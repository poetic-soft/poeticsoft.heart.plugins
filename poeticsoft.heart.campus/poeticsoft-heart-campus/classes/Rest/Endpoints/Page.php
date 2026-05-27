<?php

namespace Poeticsoft\Heart\Rest\Endpoints;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Rest\Endpoint;

/**
 * System Endpoint Section.
 * Example of modular API implementation with security levels.
 */
class Page extends Endpoint
{

    /**
     * Define routes for this section.
     */
    public function get_routes()
    {
        return [
            '/page/free-get' => [
                'methods'  => 'GET',
                'callback' => 'free_get',
                'auth'     => self::AUTH_ADMIN,
            ],
            '/page/free-update' => [
                'methods'  => 'POST',
                'callback' => 'free_update',
                'auth'     => self::AUTH_ADMIN,
            ],
        ];
    }

    /**
     * GEt status of pages.
     */
    public function free_get($request)
    {      
              
        $campus_root_id_option_name = sprintf('%sroot_post_id', Campus::PREFIX);
        $campus_root_id = intval(get_option($campus_root_id_option_name));
      
        if(!$campus_root_id) { 

            throw new Exception('Campus root id not provided', 404); 
        }
      
        $post = get_post($campus_root_id);
        if(!$post) { 

            throw new Exception('Campus root page not found', 404); 
        }
      
        $descendants = get_pages(array(
            'child_of'     => $campus_root_id,
            'post_type'    => get_post_type($campus_root_id),
            'post_status'  => [
                'publish',
                'pending', 
                'draft', 
                'private', 
                'future'          
            ],
        ));
      
        $ids = wp_list_pluck($descendants, 'ID');
        
        $ids[] = (int)$campus_root_id;
        
        
        $postsstatus = [];      
        foreach($ids as $id) {
            
            $post_status = get_post_meta(
                $id,
                Campus::PREFIX . 'status',
                true
            );
            
            $postsstatus[$id] = $post_status;
        }
            
        return $this->send_success([
            'pages' => $postsstatus,
            'time'   => current_time('mysql'),
        ]);
    }

    /**
     * Get System Info (Admin Only).
     */
    public function free_update($request)
    {
        $postid = $request->get_param('postid');
        $type = $request->get_param('isfree') ? 'free' : 'paid';
      
        if(!$postid) { 

            throw new Exception('Post id not provided', 404); 
        }
        
        $post = get_post($postid);
        if(!$post) { 

            throw new Exception('Post not found', 404); 
        }

        $update = update_post_meta(
            $postid,
            Campus::PREFIX . 'status',
            $type
        );
        
        return $this->send_success([
            'postid' => $postid,
            'updated' => $update ? 'ok' : 'ko',
            'type' => $type
        ]);
    }
}
