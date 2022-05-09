<?php

namespace Web\InterChat\Model\Request;

class AddNotificationRequest {

    private string $username;
    private string $messageFrom;
    private string $message;

    public function setUsername(string $username) {
        $this->username = $username;
    }

    public function setMessageFrom(string $messageFrom) {
        $this->messageFrom = $messageFrom;
    }

    public function setMessage(string $message) {
        $this->message = $message;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getMessageFrom(): string {
        return $this->messageFrom;
    }

    public function getMessage(): string {
        return $this->message;
    }
}