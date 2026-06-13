<?php

namespace Poeticsoft\Heart\View;

use Poeticsoft\Heart\Campus;

class View
{
    private $base_dir;


    public function __construct()
    {
        $this->base_dir = dirname(dirname(__DIR__)) . '/views/';
    }


    public function render($template_name, $data = [], $echo = true)
    {
        $file = $this->base_dir . $template_name . '.php';

        if (! file_exists($file)) {
            $error = "Template not found: {$template_name}";
            if (defined('WP_DEBUG') && WP_DEBUG) {
                echo esc_html($error);
            }
            return;
        }


        extract($data);


        ob_start();
        include $file;
        $content = ob_get_clean();

        if ($echo) {
            echo $content;
        } else {
            return $content;
        }
    }
}
