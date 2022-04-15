<?php

namespace Web\InterChat\Model\Request;

class UserCpRequest {

    private ?string $oldPassword;
    private ?string $newPassword;

    public function setOldPassword(string $oldPassword) {
        $this->oldPassword = $oldPassword;
    }

    public function getOldPassword(): string {
        return $this->oldPassword;
    }

    public function setNewPassword(string $newPassword) {
        $this->newPassword = $newPassword;
    }

    public function getNewPassword(): string {
        return $this->newPassword;
    }
}