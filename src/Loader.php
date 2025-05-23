<?php

namespace cjmustard\pocketcord;

use cjmustard\pocketcord\command\PocketCordCommand;
use cjmustard\pocketcord\database\WebhookQueries;
use cjmustard\pocketcord\listener\EventListener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use cjmustard\pocketcord\webhook\WebhookMessage;
use cjmustard\pocketcord\Webhook\WebhookAPI;
use cjmustard\pocketcord\tasks\WebhookHeartbeat;
use SQLite3;

class Loader extends PluginBase {

    public static Config $config;
    public static Loader $instance;

    public function onEnable(): void {
        self::$instance = $this;

        if(!file_exists($this->getDataFolder() . 'config.yml')) {
            $configData = $this->getResource('./config.yml');
            file_put_contents($this->getDataFolder() . 'config.yml', $configData);
        }

        self::$config = new Config($this->getDataFolder() . 'config.yml');

        new WebhookQueries(new SQLite3($this->getDataFolder() . self::$config->get('database-name')));
        new WebhookAPI();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getScheduler()->scheduleRepeatingTask(new WebhookHeartbeat(), 20);

        $this->getServer()->getCommandMap()->register('pocketcord', new PocketCordCommand($this));

        self::onStart();
    }

    public static function getInstance(): Loader {
        return self::$instance;
    }

    public function onStart(): void {
        $format = Loader::$config->get(WebhookMessage::FORMATS[WebhookAPI::START]);
        WebhookMessage::applyMessage(WebhookAPI::START, $format);
    }

    public function onDisable(): void {
        $format = Loader::$config->get(WebhookMessage::FORMATS[WebhookAPI::STOP]);
        $message = WebhookMessage::formatTime($format);
        WebhookMessage::applyMessage(WebhookAPI::STOP, $message);

        WebhookAPI::disbatchWebhooks();
    }
}
