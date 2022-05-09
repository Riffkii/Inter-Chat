<?php

namespace Web\InterChat\Repository;
use PHPUnit\Framework\TestCase;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Database\Session;
use Web\InterChat\Model\Database\User;
use PDOException;

class SessionRepositoryTest extends TestCase {

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->setUsername('admin');
        $user->setName('admin');
        $user->setPassword('123');
        $this->userRepository->save($user);
    }

    public function testSaveAndFindByIdSuccess() {
        $session = new Session();
        $session->setId(uniqid());
        $session->setUserUsername('admin');
        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->getId());

        $this->assertSame($session->getID(), $result->getId());
        $this->assertSame($session->getUserUsername(), $result->getUserUsername());
    }

    public function testSaveFailed() {
        $this->expectException(PDOException::class);
        $session = new Session();
        $session->setId(uniqid());
        $session->setUserUsername('admin');
        $this->sessionRepository->save($session);
        $this->sessionRepository->save($session);
    }

    public function testFindByIdNotFound() {
        $result = $this->sessionRepository->findById('unknown');
        $this->assertNull($result);
    }

    public function testFindAllSuccess() {
        $user = new User();
        $user->setUsername('admin2');
        $user->setName('admin2');
        $user->setPassword('123');
        $this->userRepository->save($user);

        $session = new Session();
        $session->setId(uniqid());
        $session->setUserUsername('admin');
        $this->sessionRepository->save($session);
        $session->setId(uniqid());
        $session->setUserUsername('admin2');
        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findAll();

        $this->assertSame(2, sizeof($result));
        $this->assertSame('admin', $result[0]->getUserUsername());
        $this->assertSame('admin2', $result[1]->getUserUsername());
    }

    public function testFindAllNull() {
        $result = $this->sessionRepository->findAll();
        $this->assertSame(0, sizeof($result));
    }

    public function testDeleteByIdSuccess() {
        $session = new Session();
        $session->setId(uniqid());
        $session->setUserUsername('admin');
        $this->sessionRepository->save($session);

        $this->sessionRepository->deleteById($session->getId());

        $result = $this->sessionRepository->findById($session->getId());
        $this->assertNull($result);
    }

    public function testDeleteByIdNotFound() {
        $session = new Session();
        $session->setId(uniqid());
        $session->setUserUsername('admin');
        $this->sessionRepository->save($session);

        $this->sessionRepository->deleteById('unknown');

        $result = $this->sessionRepository->findById($session->getId());
        $this->assertNotNull($result);
    }
}