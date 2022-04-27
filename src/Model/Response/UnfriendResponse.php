<?php

namespace Web\InterChat\Model\Response;
use Web\InterChat\Model\Database\Friendship;

class UnfriendResponse {

    private Friendship $friendship;

    public function setFriendship(Friendship $friendship) {
        $this->friendship = $friendship;
    }

    public function getFriendship() {
        return $this->friendship;
    }
}