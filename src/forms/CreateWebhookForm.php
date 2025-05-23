<?php

namespace cjmustard\pocketcord\Forms;

use cjmustard\pocketcord\webhook\WebhookAPI;
use cjmustard\pocketcord\lib\form\BaseForm;
use cjmustard\pocketcord\lib\form\CustomForm;
use cjmustard\pocketcord\listener\SetupListener;

class CreateWebhookForm extends BaseForm {

    public function sendForm(): void {
        $form = new CustomForm(function($player, $data) { 
            if(!isset($data)) return;
            if(!isset($data['name']) || $data['name'] == "" || ctype_space($data['name'])) return $player->sendMessage("§8(§3§cPocket§8Cord §7Webhook Management§8)§7 You need to enter a name for your webhook.");
            if(WebhookAPI::getWebhook($data['name'])) return $player->sendMessage("§8(§3§cPocket§8Cord §7Webhook Management§8)§7 There is already a webhook with this name.");
            
            $tasks = [];
            foreach($data as $taskname => $task) {
                if($taskname == 'name' || $taskname == 'label') continue;
                if($task) $tasks[] = $taskname;
            }

            ($data['avatar_url']) ? $aurl = false : $aurl = true;

            $webhookData = [
                "name" => $data['name'],
                "tasks" => $tasks,
                'url' => false,
                'avatar_url' => $aurl
            ];

            new SetupListener($player, SetupListener::CREATE, $webhookData);
        });

        $form->setTitle('§cPocket§8Cord §rWebhook Management');

        $form->addInput('Required', 'Webhook Name', null, 'name');
        $form->addToggle('Avatar URL', false, 'avatar_url');

        $form->addLabel("§7(§cNOTICE§7) §8Toggle what you want the server to flag.", 'label');

        $form->addToggle('Stops', false, WebhookAPI::STOP);
        $form->addToggle('Starts', false, WebhookAPI::START);
        $form->addToggle('Joins', false, WebhookAPI::JOIN);
        $form->addToggle('Leaves', false, WebhookAPI::LEAVE);
        $form->addToggle('Deaths', false, WebhookAPI::DEATH);
        $form->addToggle('Chats', false, WebhookAPI::CHAT);
        $form->addToggle('Commands', false, WebhookAPI::COMMAND);
        $form->addToggle('Kills', false, WebhookAPI::KILL);

        $this->player->sendForm($form);
    }
}