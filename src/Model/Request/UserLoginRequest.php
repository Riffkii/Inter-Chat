<?php

namespace Web\InterChat\Model\Request;

class UserLoginRequest {

    private string $username;
    private string $password;

    public function setUsername(string $username) {
        $this->username = $username;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function getUsername(): string {
        return $this->username;
    }
    
    public function getPassword(): string {
        return $this->password;
    }
}