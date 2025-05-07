<?php

namespace zyloxdeveloper\pocketcord\discord;

use zyloxdeveloper\pocketcord\webhook\Webhook;

class DiscordAPI {

    public static function sendWebhook(String $webhookURL, String $content): bool {
        new CurlRequest($webhookURL, $content);

        return true;
    }

    public static function sendFromObject(Webhook $webhookObject, String $content): bool {
        new CurlRequest($webhookObject->webhookURL, $content, $webhookObject->name, $webhookObject->imageURL);

        return true;
    }
}