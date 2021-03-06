<?php

namespace Web\InterChat\Controller;
require_once __DIR__ . '/../Helper/Helper.php';
use PHPUnit\Framework\TestCase;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Service\UserService;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Request\UserRegisterRequest;
use Web\InterChat\Exception\ValidationException;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Service\SessionService;
use Web\InterChat\Repository\NotificationRepository;

class UserControllerTest extends TestCase {

    private UserRepository $userRepository;
    private UserService $userService;
    private UserController $userController;
    private SessionService $sessionService;
     
    protected function setUp(): void {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userService = new UserService($this->userRepository, $sessionRepository);
        $this->userController = new UserController();
        $this->sessionService = new SessionService($sessionRepository, $this->userRepository);
        $notificationsRepository = new NotificationRepository(Database::getConnection());

        putenv('type=test');
        $sessionRepository->deleteAll();
        $notificationsRepository->deleteAll();
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

    public function testLogout() {
        $this->registerHelper();
        $result = $this->sessionService->create('admin');
        $_COOKIE['X-LOG-SESSION'] = $result->getId();

        $this->userController->logout();

        $this->expectOutputRegex('[Location: /]');
    }

    public function testChangeName() {
        $this->userController->changeName();
        $this->expectOutputRegex('[Change Name]');
    }

    public function testPostChangeNameSuccess() {
        $this->registerHelper();
        $result = $this->sessionService->create('admin');
        $_COOKIE['X-LOG-SESSION'] = $result->getId();
        $_POST['cn'] = 'yntkts';

        $this->userController->postChangeName();

        $this->expectOutputRegex('[Location: /]');
    }

    public function testPostChangeNameBlank() {
        $this->registerHelper();
        $result = $this->sessionService->create('admin');
        $_COOKIE['X-LOG-SESSION'] = $result->getId();
        $_POST['cn'] = '';

        $this->userController->postChangeName();

        $this->expectOutputRegex('[Name can not blank]');
    }

    public function testChangePassword() {
        $this->userController->changePassword();
        $this->expectOutputRegex('[Change Password]');
    }

    public function testChangePasswordBlankOldPassword() {
        $this->registerHelper();
        $result = $this->sessionService->create('admin');
        $_COOKIE['X-LOG-SESSION'] = $result->getId();
        $_POST['op'] = '';
        $_POST['np'] = 'gtw';

        $this->userController->postChangePassword();

        $this->expectOutputRegex('[Old Password or New Password can not blank]');
    }

    public function testChangePasswordBlankNewPassword() {
        $this->registerHelper();
        $result = $this->sessionService->create('admin');
        $_COOKIE['X-LOG-SESSION'] = $result->getId();
        $_POST['op'] = '123';
        $_POST['np'] = '';

        $this->userController->postChangePassword();

        $this->expectOutputRegex('[Old Password or New Password can not blank]');
    }

    public function testChangePasswordWrongOldPassword() {
        $this->registerHelper();
        $result = $this->sessionService->create('admin');
        $_COOKIE['X-LOG-SESSION'] = $result->getId();
        $_POST['op'] = '12';
        $_POST['np'] = 'gtw';

        $this->userController->postChangePassword();

        $this->expectOutputRegex('[Old Password is wrong]');
    }

    public function testGetUsername() {
        $data = $this->userController->getUsername();
        $this->assertSame("as", $data);
    }
}  