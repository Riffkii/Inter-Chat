<?php

namespace Web\InterChat\Model\Database;

class Session {

    private string $id;
    private string $userUsername;

    public function setId(string $id) {
        $this->id = $id;
    }

    public function setUserUsername(string $userUsername) {
        $this->userUsername = $userUsername;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getUserUsername(): string {
        return $this->userUsername;
    }
}