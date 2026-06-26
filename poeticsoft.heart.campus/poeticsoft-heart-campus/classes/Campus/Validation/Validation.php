<?php

namespace Poeticsoft\Heart\Campus\Validation;

use Poeticsoft\Heart\Campus\Utils\Utils;

class Validation
{
    public function sanitize($value, $type = 'text')
    {
        return Utils::sanitize($value, $type);
    }

    public function validate($value, $rule)
    {
        return Utils::validate($value, $rule);
    }

    public function validate_schema($data, $schema)
    {
        return Utils::validate_schema($data, $schema);
    }
}
