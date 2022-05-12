<?php

namespace Web\InterChat\Model\Request;

class SendMessageRequest {

    private string $message;
    private string $targetUser;

    public function setMessage(string $message) {
        $this->message = $message;
    }

    public function setTargetUser(string $targetUser) {
        $this->targetUser = $targetUser;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getTargetUser() {
        return $this->targetUser;
    }
}