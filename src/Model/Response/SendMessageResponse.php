<?php

namespace Web\InterChat\Model\Response;
use Web\InterChat\Model\Database\Chat;

class SendMessageResponse {

    private Chat $chat;

    public function setChat(Chat $chat) {
        $this->chat = $chat;
    }

    public function getChat() {
        return $this->chat;
    }
}