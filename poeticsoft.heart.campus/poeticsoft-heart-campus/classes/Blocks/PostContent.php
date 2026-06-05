<?php

namespace Poeticsoft\Heart\Blocks;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Validation\Access;
use Poeticsoft\Heart\Utils\Utils;

/**
 * Campus Post Content
 */
class PostContent
{

    /**
     * Constructor.
     */
    public function __construct() {      
        
        add_action(
            'wp_enqueue_scripts',
            [$this, 'init']
        );
        
        add_filter(
            'render_block_core/post-content',
            [$this, 'render_post_content'],
            10,
            2
        );
    }

	/**
	 * Inicio.
	 */
	public function init() {
        
        global $post;
        
        if(!$post) {            
            return;
        }  
        
        $post_is_in_campus = Campus::get(Access::class)->post_is_in_campus($post->ID);
        
        if(!$post_is_in_campus) {            
            return;
        }  

        wp_enqueue_script(
          Campus::PLUGIN_SLUG . '-postcontent',
          Utils::url('ui/frontend/postcontent/main.js'),
          [
            'jquery'
          ],
          filemtime(Utils::path('ui/frontend/postcontent/main.js')),
          true
        );

        wp_enqueue_style(
          Campus::PLUGIN_SLUG . '-postcontent',
          Utils::url('ui/frontend/postcontent/main.css'),
          [],
          filemtime(Utils::path('ui/frontend/postcontent/main.css')),
          'all'
        );  
        
        $access_by_option_name = Campus::PREFIX . 'access_by';
        $access_by = get_option($access_by_option_name);
        $data_json = json_encode($access_by);
        $prefix = Campus::PREFIX;
        $inline_js = "var {$prefix}access_by = {$data_json};";

        wp_add_inline_script(
            Campus::PLUGIN_SLUG . '-postcontent',
            $inline_js, 
            'after'
        );
        
        wp_enqueue_script(          
          Campus::PLUGIN_SLUG . '-register-access',
          Utils::url('ui/frontend/registeraccess/main.js'),
          [
            'jquery'
          ],
          filemtime(Utils::path('ui/frontend/registeraccess/main.js')),
          true
        ); 
        
        $post_id = get_the_ID();
        $validate_email = Campus::get(Access::class)->validate_email();
        $email = $validate_email ? $validate_email : 'anonymous';
        $ip = Utils::get_request_ip();
        $accessdata = $post_id . '||' . $email . '||' . $ip;
        $campus_access_data_key = Campus::PREFIX . 'register_access_data';
        $inline_js = "var {$campus_access_data_key} = '{$accessdata}';";
        wp_add_inline_script(
          Campus::PLUGIN_SLUG . '-register-access', 
          $inline_js, 
          'before'
        );        
    }
    
    public function render_post_content($block_content, $block) {

        global $post;

        if(!$post) {

          return '';
        }

        if(Campus::get(Access::class)->can_access($post->ID)) {

          return $this->render_access_messages($block_content);          
        }    

        return $this->render_access_form(
          $post->ID, 
          $block['attrs']
        );  
    }
  
    private function render_access_messages($block_content) {
        
        if ( 
            current_user_can('manage_options')
            &&
            Campus::get(Utils::class)->get_allow_admin()
        ) {
        
            return '<div class="ViewAsAdmin">
                Vista de administrador 
                (<a href="/wp-login.php?action=logout">SALIR</a>) 
            </div>' . 
            $block_content;        
        }
        
        return $block_content;    
    }
    
    private function render_access_form($post_id, $block_attrs) {
    
        $show_restricted_text = isset($block_attrs['showRestrictedText']) ?
        $block_attrs['showRestrictedText'] : '';        
        $post_child_ids = get_posts([
            'post_type' => 'page',
            'posts_per_page' => -1,
            'post_parent' => $post_id,
            'fields' => 'ids'
        ]);

        if(
            $show_restricted_text == 'hiddenalways'
            ||
            (
                $show_restricted_text == 'onlyincontents'
                &&
                count($post_child_ids)
            )
        ) {

            return '';
        }

        $access_by_option_name = Campus::PREFIX . 'access_by';
        $access_by = get_option($access_by_option_name);
        
        $restricted_visible_text = isset($block_attrs['restrictedVisibleText']) ?
        $block_attrs['restrictedVisibleText']
        :
        '';
        
        $valid_user_mail = Campus::get(Access::class)->validate_email();   
        if($valid_user_mail) { 

            return '<div
                class="wp-block-poeticsoft-heart-campus-postcontent"
                data-email="' . esc_attr($valid_user_mail) . '"
                data-post_id="' . esc_attr($post_id) . '"
            >
                <div class="Forms CantAccess">
                <div class="AdviceText">' . 
                    $restricted_visible_text . 
                '</div>
                <div class="Dummy">NO TIENES ACCESO</div>
                </div>
            </div>';

        } else {

            return '<div class="wp-block-poeticsoft-heart-campus-postcontent">
                <div class="Forms Identify">IDENTIFICADOR</div>
            </div>';
        }
    }
}