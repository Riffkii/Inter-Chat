<?php

namespace Web\InterChat\Model\Request;

class UserCnRequest {

    private ?string $name;

    public function setName(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }
}