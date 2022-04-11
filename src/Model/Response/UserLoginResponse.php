<?php

namespace Web\InterChat\Model\Response;
use Web\InterChat\Model\Database\User;

class UserLoginResponse {

    private User $user;

    public function setUser(User $user) {
        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }
}