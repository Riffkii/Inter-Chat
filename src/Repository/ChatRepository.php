<?php

namespace Web\InterChat\Repository;
use PDO;
use Web\InterChat\Model\Database\Chat;

class ChatRepository {

    public function __construct(private PDO $connection) {}

    public function save(Chat $chat) {
        $sql = 'INSERT INTO chats(from_user, message, target_user) VALUES(?, ?, ?)';
        $result = $this->connection->prepare($sql);
        $result->execute([$chat->getFromUser(), $chat->getMessage(), $chat->getTargetUser()]);
    }

    public function findChatsByFUAndTU(Chat $chat): array {
        $sql = 'SELECT * FROM chats WHERE (from_user = ? AND target_user = ?) OR (from_user = ? AND target_user = ?) ORDER BY time_stamp';
        $result = $this->connection->prepare($sql);
        $result->execute([$chat->getFromUser(), $chat->getTargetUser(), $chat->getTargetUser(), $chat->getFromUser()]);

        $data = [];
        while($row = $result->fetch()) {
            $chat = new Chat();
            $chat->setId($row['id']);
            $chat->setFromUser($row['from_user']);
            $chat->setMessage($row['message']);
            $chat->setTargetUser($row['target_user']);
            $chat->setTimestamp($row['time_stamp']);
            $data[] = $chat;
        }
        
        return $data;
    }

    public function deleteAll() {
        $sql = 'DELETE FROM chats';
        $this->connection->exec($sql);
    }
}