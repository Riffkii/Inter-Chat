<?php

namespace Web\InterChat\Model\Database;

class Friendship {

    private string $user1Username;
    private string $user2Username;

    public function setUser1Username(string $user1Username) {
        $this->user1Username = $user1Username;
    }

    public function setUser2Username(string $user2Username) {
        $this->user2Username = $user2Username;
    }

    public function getUser1Username(): string {
        return $this->user1Username;
    }

    public function getUser2Username(): string {
        return $this->user2Username;
    }
}