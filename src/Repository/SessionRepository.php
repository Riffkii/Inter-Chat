<?php

namespace Web\InterChat\Repository;
use PDO;
use Web\InterChat\Model\Database\Session;

class SessionRepository {

    public function __construct(private PDO $connection) {}

    public function save(Session $session) {
        $sql = 'INSERT INTO sessions(id, user_username) VALUES(?, ?)';
        $result = $this->connection->prepare($sql);
        $result->execute([$session->getId(), $session->getUserUsername()]);
    }

    public function findById(string $id): ?Session {
        $sql = 'SELECT * FROM sessions WHERE id = ?';
        $result = $this->connection->prepare($sql);
        $result->execute([$id]);

        if($row = $result->fetch()) {
            $session = new Session();
            $session->setId($row['id']);
            $session->setUserUsername($row['user_username']);
            return $session;
        }

        return null;
    }

    public function deleteById(string $id) {
        $sql = 'DELETE FROM sessions WHERE id = ?';
        $result = $this->connection->prepare($sql);
        $result->execute([$id]);
    }

    public function deleteAll() {
        $sql = 'DELETE FROM sessions';
        $this->connection->exec($sql);
    }
}