<?php

namespace Web\InterChat\Model\Request;

class FindFriendRequest {

    private string $toUser;

    public function setToUser(string $toUser) {
        $this->toUser = $toUser;
    }

    public function getToUser(): string {
        return $this->toUser;
    }
}