<?php

namespace Poeticsoft\Heart\Rest\Endpoints;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Rest\Endpoint;
use Poeticsoft\Heart\Utils\Utils
;

/**
 * System Endpoint Section.
 * Example of modular API implementation with security levels.
 */
class Identify extends Endpoint
{

    /**
     * Define routes for this section.
     */
    public function get_routes()
    {
        return [
            '/identify/subscriber/identify' => [
                'methods'  => 'POST',
                'callback' => 'identify_subscriber_identify',
                'auth'     => self::AUTH_USER,
            ],
            '/identify/subscriber/confirmcode' => [
                'methods'  => 'POST',
                'callback' => 'identify_subscriber_confirmcode',
                'auth'     => self::AUTH_USER,
            ]
        ];
    }

    public function identify_subscriber_identify($request)
    {   
        
      $email = $request->get_param('email');

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        return $this->send_error( 
          'IDENTIFY_MAIL_INVALID',  
          __('El correo no está registrado'), 
          $status = 404 
        );

      } else {                

        global $wpdb;
        $table_name = $wpdb->prefix . Campus::PREFIX . 'access';
        $access = $wpdb->get_results(
          $wpdb->prepare(
            "SELECT
            id,
            user_mail
            FROM {$table_name}
            WHERE user_mail = %s",
            $email
          )
        );

        if(count($access)) {          

          $identify_code = Utils::get_identify_code($email);
            
          return $this->send_success([
            'code' => $identify_code,
            'time'   => current_time('mysql'),
          ]);

        } else {

          return $this->send_error( 
            'IDENTIFY_MAIL_NOTFOUND', 
            __('El correo no está registrado'), 
            $status = 404 
          );
        }
      }
    }

    public function identify_subscriber_confirmcode($request)
    {   

      $email = $request->get_param('email');
      $code = $request->get_param('code');    
      $cookieemail = isset($_COOKIE['useremail']) ? $_COOKIE['useremail'] : null;
      $cookiecode = isset($_COOKIE['usercode']) ? $_COOKIE['usercode'] : null;

      if(
        $cookieemail
        &&
        $cookiecode
        &&
        $email == $cookieemail
        &&
        $code == $cookiecode
      ) { 

        setcookie(
          'codeconfirmed',
          'yes',
          0,
          '/',
          COOKIE_DOMAIN,
          is_ssl(),
          true
        );      

        return $this->send_success([
          'time'   => current_time('mysql'),
        ]);

      } else {

        unset($_COOKIE['useremail']);
        unset($_COOKIE['usercode']);
        unset($_COOKIE['codeconfirmed']);
        setcookie('useremail', '', time() - 3600, '/');
        setcookie('usercode', '', time() - 3600, '/');
        setcookie('codeconfirmed', '', time() - 3600, '/');

        return $this->send_error( 
            'IDENTIFY_USER_CODE_INVALID', 
            __('El código no es correcto'), 
            $status = 406
          );
      }
    }
}
