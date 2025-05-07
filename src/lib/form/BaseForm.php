<?php

namespace cjmustard\pocketcord\lib\form;

use pocketmine\player\Player;

abstract class BaseForm {

    protected Player $player;
    protected array $data;

    public function __construct(Player $player, ?array $data = []) {
        $this->player = $player;
        $this->data = $data;
        $this->sendForm();
    }

    abstract public function sendForm();
}