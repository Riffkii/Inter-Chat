<?php

namespace Web\InterChat\Model\Response;

class FindFriendResponse {

    private array $friends;

    public function setFriends(array $friends) {
        $this->friends = $friends;
    }

    public function getFriends() {
        return $this->friends;
    }
}