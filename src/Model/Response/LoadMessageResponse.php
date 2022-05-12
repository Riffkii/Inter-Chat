<?php

namespace Web\InterChat\Model\Response;

class LoadMessageResponse {

    private array $chats;

    public function setChats(array $chats) {
        $this->chats = $chats;
    }

    public function getChats() {
        return $this->chats;
    }
}