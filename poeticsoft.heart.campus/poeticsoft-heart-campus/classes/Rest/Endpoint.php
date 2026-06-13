<?php

namespace Poeticsoft\Heart\Rest;

use Poeticsoft\Heart\Utils\Utils;

abstract class Endpoint
{
    const AUTH_PUBLIC = 'public';
    const AUTH_USER   = 'user';
    const AUTH_ADMIN  = 'admin';

    abstract public function get_routes();

    public function send_success($data, $status = 200)
    {
        return Utils::send_success($data, $status);
    }


    public function send_error($code, $message, $status = 400)
    {
        return Utils::send_error($code, $message, $status);
    }
}
