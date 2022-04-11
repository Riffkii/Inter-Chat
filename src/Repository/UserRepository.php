<?php

namespace Web\InterChat\Repository;
use PDO;
use Web\InterChat\Model\Database\User;

class UserRepository implements Repository{

    public function __construct(private PDO $connection) {}

    public function save(User $user) {
        $sql = 'INSERT INTO users(username, name, password) VALUES (?, ?, ?)';
        $result = $this->connection->prepare($sql);
        $result->execute([$user->getUsername(), $user->getName(), $user->getPassword()]);
    }

    public function findByUsername(string $username): ?User {
        $sql = 'SELECT * FROM users WHERE username = ?';
        $result = $this->connection->prepare($sql);
        $result->execute([$username]);

        if($row = $result->fetch()) {
            $user = new User();
            $user->setUsername($row['username']);
            $user->setName($row['name']);
            $user->setPassword($row['password']);
            return $user;
        }

        return null;
    }

    public function deleteAll() {
        $sql = 'DELETE FROM users';
        $this->connection->exec($sql);
    }
}