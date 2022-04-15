<?php

namespace Web\InterChat\Model\Response;
use Web\InterChat\Model\Database\User;

class UserCnResponse {

    private User $user;

    public function setUser(User $user) {
        $this->user = $user;
    }

    public function getUser(): User {
        return $this->user;
    }
}