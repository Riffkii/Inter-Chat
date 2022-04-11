<?php

namespace Web\InterChat\Util;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase {

    public function testConnection() {
        $connection = Database::getConnection();
        $this->assertNotNull($connection);
    }
}