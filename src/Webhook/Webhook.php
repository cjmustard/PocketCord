<?php

namespace zyloxdeveloper\pocketcord\webhook;

class Webhook {

    public String $webhookURL = "";
    public String $name = "";
    public ?String $imageURL = "";
    public ?Array $tasks = [];

    public function __construct(
        String $name,
        String $webhookURL,
        ?String $imageURL = "",
        ?Array $tasks = []
    ) {
        $this->webhookURL = $webhookURL;
        $this->name = $name;
        $this->imageURL = $imageURL;
        $this->tasks = $tasks;
    }
}