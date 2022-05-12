<?php

namespace Web\InterChat\Repository;
use PDO;
use Web\InterChat\Model\Database\Notification;

class NotificationRepository {

    public function __construct(private PDO $connection) {}

    public function save(Notification $notification): string {
        //$this->lowerCase($notification);
        $sql = "INSERT INTO notifications(username, message_from, message) VALUES(?, ?, ?)";
        $result = $this->connection->prepare($sql);
        $result->execute([$notification->getUsername(), $notification->getMessageFrom(), $notification->getMessage()]);
        return $this->connection->lastInsertId();
    }

    public function getMessagesByUsername(string $username): array {
        $sql = "SELECT * FROM notifications WHERE username = ?";
        $result = $this->connection->prepare($sql);
        $result->execute([$username]);

        $datas = [];
        while($row = $result->fetch()) {
            $notification = new Notification();
            $notification->setID($row['id']);
            $notification->setUsername($row['username']);
            $notification->setMessageFrom($row['message_from']);
            $notification->setMessage($row['message']);
            $datas[] = $notification;
        }

        return $datas;
    }

    public function getMessageByMF(string $messageFrom): array {
        $sql = "SELECT * FROM notifications WHERE message_from = ?";
        $result = $this->connection->prepare($sql);
        $result->execute([$messageFrom]);

        $datas = [];
        while($row = $result->fetch()) {
            $notification = new Notification();
            $notification->setID($row['id']);
            $notification->setUsername($row['username']);
            $notification->setMessageFrom($row['message_from']);
            $notification->setMessage($row['message']);
            $datas[] = $notification;
        }

        return $datas;
    }

    public function getMessageByMFAndUsername(string $messageFrom, string $username): array {
        $datas = $this->getMessagesByUsername($username);
        
        $value = [];
        foreach($datas as $data) {
            if($data->getMessageFrom() == $messageFrom) {
                $value[] = $data;
            }
        }

        return $value;
    }

    public function deleteById(int $id) {
        $sql = "DELETE FROM notifications WHERE id = ?";
        $result = $this->connection->prepare($sql);
        $result->execute([$id]);
    }

    public function deleteAll() {
        $sql = "DELETE FROM notifications";
        $this->connection->exec($sql);
    }

    // private function lowerCase(Notification $notification) {
    //     $str = strtolower($notification->getUsername());
    //     $notification->setUsername($str);
    // }
}