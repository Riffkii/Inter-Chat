<?php

namespace Web\InterChat\Model\Request;

class UserRegisterRequest {

    private ?string $username = null;
    private ?string $name = null;
    private ?string $password = null;

    public function setUsername(string $username) {
        $this->username = $username;
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function getName(): ?string {
        return $this->name;
    }
    
    public function getPassword(): ?string {
        return $this->password;
    }
}