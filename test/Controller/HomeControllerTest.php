<?php

namespace Web\InterChat\Controller;
use PHPUnit\Framework\TestCase;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Model\Database\User;
use Web\InterChat\Model\Database\Session;

class HomeControllerTest extends TestCase {
    
    private HomeController $homeController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->homeController = new HomeController();

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testGuest() {
        $this->homeController->index();
        $this->expectOutputRegex('[Welcome]');
    }

    public function testUser() {
        $user = new User();
        $user->setUsername('admin');
        $user->setName('admin');
        $user->setPassword('123');
        $this->userRepository->save($user);

        $session = new Session();
        $session->setId(uniqid());
        $session->setUserUsername($user->getUsername());
        $this->sessionRepository->save($session);

        $_COOKIE['X-LOG-SESSION'] = $session->getId();

        $this->homeController->index();

        $this->expectOutputRegex('[Welcome admin]');
    }
}