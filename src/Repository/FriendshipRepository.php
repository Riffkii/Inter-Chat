<?php

namespace Web\InterChat\Repository;
use PDO;
use Web\InterChat\Model\Database\Friendship;
use Web\InterChat\Util\Set;
use Web\InterChat\Model\Database\User;

class FriendshipRepository {
    
    public function __construct(private PDO $connection) {}

    public function save(Friendship $friendship) {
        $this->sort($friendship);
        $sql = "INSERT INTO friendships(user_1_username, user_2_username) VALUES(?, ?)";
        $result = $this->connection->prepare($sql);
        $result->execute([$friendship->getUser1Username(), $friendship->getUser2Username()]);
    }

    public function findFriendsByUsername(string $username): array {
        $sql = "SELECT a.user_1_username, a.user_2_username, user_1.name AS name_1, user_2.name AS name_2 FROM friendships AS a
                JOIN users AS user_1 ON (a.user_1_username = user_1.username)
                JOIN users AS user_2 ON (a.user_2_username = user_2.username)
                WHERE a.user_1_username = ? OR a.user_2_username = ?";
        $result = $this->connection->prepare($sql);
        $result->execute([$username, $username]);

        $data = [];
        while($row = $result->fetch()) {
            if($row['user_1_username'] == $username) {
                $user = new User();
                $user->setUsername($row['user_2_username']);
                $user->setName($row['name_2']);
                $data[] = $user;
            } else {
                $user = new User();
                $user->setUsername($row['user_1_username']);
                $user->setName($row['name_1']);
                $data[] = $user;
            }
        }
        return $data;
    }

    public function findNotFriendsByUsername(string $username): array {
        $data = new Set();
        $users = $this->getAllUser();
        $friends = $this->findFriendsByUsername($username);
        
        foreach($users as $user) {
            $data->add($user);
            foreach($friends as $friend) {
                if($user->getUsername() == $friend->getUsername()) {
                    $data->delete($user);
                    break;
                }
            }
            if($user->getUsername() == $username) {
                $data->delete($user);
            }
        }
        return $data->getArrayCopy();
    }

    public function findFriendByName(Friendship $friendship): array {
        $this->lowerCase($friendship);

        $friends = $this->findFriendsByUsername($friendship->getUser1Username());
        $data = [];

        foreach($friends as $friend) {
            if(str_contains(strtolower($friend->getName()), $friendship->getUser2Username())) {
                $user = new User();
                $user->setUsername($friend->getUsername());
                $user->setName($friend->getName());
                $data[] = $user;
            }
        }

        return $data;
    }

    public function findNotFriendByName(Friendship $friendship): array {
        $this->lowerCase($friendship);

        $notFriends = $this->findNotFriendsByUsername($friendship->getUser1Username());
        $data = [];

        foreach($notFriends as $notFriend) {
            if(str_contains(strtolower($notFriend->getName()), $friendship->getUser2Username())) {
                $user = new User();
                $user->setUsername($notFriend->getUsername());
                $user->setName($notFriend->getName());
                $data[] = $user;
            }
        }

        return $data;
    }

    public function deleteFriendsByUsername(Friendship $friendship) {
        $this->sort($friendship);
        $sql = "DELETE FROM friendships WHERE user_1_username = ? AND user_2_username = ?";
        $result = $this->connection->prepare($sql);
        $result->execute([$friendship->getUser1Username(), $friendship->getUser2Username()]);
    }

    public function deleteAll() {
        $sql = "DELETE FROM friendships";
        $this->connection->exec($sql);
    }

    private function lowerCase(Friendship &$friendship) {
        $str1 = strtolower($friendship->getUser1Username());
        $str2 = strtolower($friendship->getUser2Username());

        $friendship->setUser1Username($str1);
        $friendship->setUser2Username($str2);
    }

    private function sort(Friendship $friendship) {
        $str1 = $friendship->getUser1Username();
        $str2 = $friendship->getUser2Username();
        if(strcmp($str1, $str2) > 0) {
            $temp = $str1;
            $friendship->setUser1Username($str2);
            $friendship->setUser2Username($temp);
        } else {
            $friendship->setUser1Username($str1);
            $friendship->setUser2Username($str2);
        }
    }

    private function getAllUser(): array {
        $sql = "SELECT * FROM users";
        $result = $this->connection->query($sql);
        
        $users = [];
        while($row = $result->fetch()) {
            $user = new User();
            $user->setUsername(strtolower($row['username']));
            $user->setName($row['name']);
            $users[] = $user;
        }

        return $users;
    }
}