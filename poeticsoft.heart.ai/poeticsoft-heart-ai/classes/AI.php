<?php

namespace Poeticsoft\Heart;

use Poeticsoft\Heart\AIAgent\AIAgent;
use Poeticsoft\Heart\API\API;
use Poeticsoft\Heart\Blocks\Blocks;
use Poeticsoft\Heart\Credentials\Credentials;
use Poeticsoft\Heart\Prompts\Prompts;
use Poeticsoft\Heart\Telemetry\Telemetry;

class AI {
    public function __construct() {
        new AIAgent();
        new API();
        new Blocks();
        new Credentials();
        new Prompts();
        new Telemetry();
    }
}
