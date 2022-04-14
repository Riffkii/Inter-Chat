<?php

namespace Web\InterChat\Service;
require_once __DIR__ . '/../Helper/Helper.php';
use PHPUnit\Framework\TestCase;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Database\User;
use Web\InterChat\Model\Database\Session;

class SessionServiceTest extends TestCase {

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    private SessionService $sessionService;

    protected function setUp(): void {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->setUsername('admin');
        $user->setName('admin');
        $user->setPassword('123');
        $this->userRepository->save($user);
    }

    public function testCreate() {
        $result = $this->sessionService->create('admin');
        $response = $this->sessionRepository->findById($result->getId());

        $this->assertNotNull($response);
        $this->expectOutputRegex('[X-LOG-SESSION]');
    }

    public function testDestroy() {
        $result = $this->sessionService->create('admin');
        $_COOKIE['X-LOG-SESSION'] = $result->getId();
        $this->sessionService->destroy();

        $response = $this->sessionRepository->findById($result->getId());

        $this->assertNull($response);
        $this->expectOutputRegex('[X-LOG-SESSIONX-LOG-SESSION]');
    }

    public function testCurrent() {
        $result = $this->sessionService->create('admin');
        $_COOKIE['X-LOG-SESSION'] = $result->getId();

        $user = $this->sessionService->current();
        $this->assertNotNull($user);
        $this->assertSame($result->getUserUsername(), $user->getUsername());
    }
}