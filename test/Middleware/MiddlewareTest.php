<?php

namespace Web\InterChat\Middleware;
require_once __DIR__ . '/../Helper/Helper.php';
use PHPUnit\Framework\TestCase;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Service\UserService;
use Web\InterChat\Controller\UserController;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Service\SessionService;

class MiddlewareTest extends TestCase {

    private UserController $userController;
    private SessionService $sessionService;
     
    protected function setUp(): void {
        $userRepository = new UserRepository(Database::getConnection());
        $userService = new UserService($userRepository);
        $this->userController = new UserController($userService);
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);

        putenv('mode=test');
        $sessionRepository->deleteAll();
        $userRepository->deleteAll();
    }

    public function testMustLogin() {
        $m = new MustLogin();
        $m->before();
        $this->expectOutputRegex('[Location: /user/login]');
    }

    public function testMustNotLogin() {
        $_POST['username'] = 'admin';
        $_POST['name'] = 'admin';
        $_POST['password'] = '123';
        $this->userController->postRegister();
        $this->sessionService->create('admin');

        $m = new MustNotLogin();
        $m->before();
        $this->expectOutputRegex('[Location: /]');
    }
}