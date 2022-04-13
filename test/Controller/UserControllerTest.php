<?php

namespace Web\InterChat\Controller;
require_once __DIR__ . '/../Helper/Helper.php';
use PHPUnit\Framework\TestCase;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Service\UserService;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Request\UserRegisterRequest;
use Web\InterChat\Exception\ValidationException;

class UserControllerTest extends TestCase {

    private UserRepository $userRepository;
    private UserService $userService;
    private UserController $userController;
     
    protected function setUp(): void {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userService = new UserService($this->userRepository);
        $this->userController = new UserController($this->userService);

        putenv('mode=test');
        $this->userRepository->deleteAll();
    }

    private function registerHelper() {
        $_POST['username'] = 'admin';
        $_POST['name'] = 'admin';
        $_POST['password'] = '123';
        $this->userController->postRegister();
    }

    public function testRegister() {
        $this->userController->register();
        $this->expectOutputRegex('[Register]');
    }

    public function testRegisterSuccess() {
        $this->registerHelper();
        $result = $this->userRepository->findByUsername('admin');

        $this->assertNotNull($result);
        $this->expectOutputRegex('[Location: /user/login]');
    }

    public function testRegisterBlank() {
        $_POST['username'] = 'admin';
        $_POST['name'] = '';
        $_POST['password'] = '123';
        $this->userController->postRegister();

        $result = $this->userRepository->findByUsername('admin');

        $this->assertNull($result);
        $this->expectOutputRegex('[Username, Name, or Password can not blank]');
    }

    public function testRegisterDuplicate() {
        $this->registerHelper();
        $this->userController->postRegister();
        $this->userController->postRegister();

        $this->expectOutputRegex('[User already exist]');
    }

    public function testLogin() {
        $this->userController->login();
        $this->expectOutputRegex('[Login]');
    }

    public function testLoginSuccess() {
        $this->registerHelper();

        $_POST['username'] = 'admin';
        $_POST['password'] = '123';
        $this->userController->postLogin();

        $this->expectOutputRegex('[Location: /]');
    }

    public function testLoginBlank() {
        $this->registerHelper();

        $_POST['username'] = 'admin';
        $_POST['password'] = '';
        $this->userController->postLogin();

        $this->expectOutputRegex('[Username or Password can not blank]');
    }

    public function testLoginWrong() {
        $this->registerHelper();

        $_POST['username'] = 'admin';
        $_POST['password'] = 'wrong';
        $this->userController->postLogin();

        $this->expectOutputRegex('[Username or Password is wrong]');
    }
}  