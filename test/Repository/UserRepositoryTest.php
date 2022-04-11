<?php

namespace Web\InterChat\Repository;
use PHPUnit\Framework\TestCase;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Database\User;
use PDOException;

class UserRepositoryTest extends TestCase {

    private UserRepository $userRepository;

    protected function setUp(): void {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();
    }
    
    public function testSaveAndFindByUsernameSuccess() {
        $user = new User();
        $user->setUsername('admin');
        $user->setName('admin');
        $user->setPassword('123');

        $this->userRepository->save($user);

        $result = $this->userRepository->findByUsername('admin');

        $this->assertSame($user->getUsername(), $result->getUsername());
        $this->assertSame($user->getName(), $result->getName());
        $this->assertSame($user->getPassword(), $result->getPassword());
    }

    public function testSaveFailed() {
        $this->expectException(PDOException::class);
        $user = new User();
        $user->setUsername('admin');
        $user->setName('admin');
        $user->setPassword('123');

        $this->userRepository->save($user);
        $this->userRepository->save($user);
    }

    public function testFindByUsernameFailed() {
        $result = $this->userRepository->findByUsername("unknown");
        $this->assertNull($result);
    }
}