<?php

namespace Web\InterChat\Util;
use PDO;

class Database {

    private static ?PDO $connection = null;

    public static function getConnection(string $mode = "test"): PDO {
        if(self::$connection == null) {
            $username = 'root';
            $password = 'a123';
            $util = DatabaseUtil::connect();

            self::$connection = new PDO($util[$mode], $username, $password);
        }

        return self::$connection;
    } 
}