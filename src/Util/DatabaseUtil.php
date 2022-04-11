<?php

namespace Web\InterChat\Util;

class DatabaseUtil {

    public static function connect(): array {
        return [
            "app" => "mysql:host=localhost:3306;dbname=inter_chat",
            "test" => "mysql:host=localhost:3306;dbname=inter_chat_test"
        ];
    }
}