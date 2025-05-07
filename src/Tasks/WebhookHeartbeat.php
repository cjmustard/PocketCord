<?php

namespace cjmustard\pocketcord\tasks;

use cjmustard\pocketcord\webhook\WebhookAPI;
use cjmustard\pocketcord\Loader;
use pocketmine\scheduler\Task;

class WebhookHeartbeat extends Task {

    private int $heartbeat = 1;
    private int $time = 5;

    public function __construct() {
        $this->time = Loader::$config->get('webhook-send-rate');
    }

    final function onRun(): void {
        if($this->heartbeat >= $this->time) {
            WebhookAPI::disbatchWebhooks();
            $this->heartbeat = 1;
            return;
        }

        $this->heartbeat++;
    }
}