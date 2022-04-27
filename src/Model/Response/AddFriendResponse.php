<?php

namespace Web\InterChat\Model\Response;
use Web\InterChat\Model\Database\Friendship;

class AddFriendResponse {

    private Friendship $friendship;

    public function setFriendship(Friendship $friendship) {
        $this->friendship = $friendship;
    }

    public function getFriendship() {
        return $this->friendship;
    }
}