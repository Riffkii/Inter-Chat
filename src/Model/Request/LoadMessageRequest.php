<?php

namespace Web\InterChat\Model\Request;

class LoadMessageRequest {

    private string $targetUser;

    public function setTargetUser(string $targetUser) {
        $this->targetUser = $targetUser;
    }

    public function getTargetUser() {
        return $this->targetUser;
    }
}