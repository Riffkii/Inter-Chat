<?php

namespace Web\InterChat\Model\Response;

class FindNotFriendResponse {
    
    private array $notFriends;

    public function setNotFriends(array $notFriends) {
        $this->notFriends = $notFriends;
    }

    public function getNotFriends() {
        return $this->notFriends;
    }
}