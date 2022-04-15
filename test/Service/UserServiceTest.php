<?php

namespace Web\InterChat\Service;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Request\UserRegisterRequest;
use PHPUnit\Framework\TestCase;
use Web\InterChat\Exception\ValidationException;
use Web\InterChat\Model\Request\UserLoginRequest;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Model\Request\UserCnRequest;
use Web\InterChat\Model\Database\Session;
use Web\InterChat\Model\Request\UserCpRequest;

class UserServiceTest extends TestCase {

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    private UserService $userService;

    protected function setUp(): void {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userService = new UserService($this->userRepository, $this->sessionRepository);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testRegisterSuccess() {
        $request = new UserRegisterRequest();
        $request->setUsername('admin');
        $request->setName('admin');
        $request->setPassword('123');

        $response = $this->userService->register($request);

        $this->assertSame($request->getUsername(), $response->getUser()->getUsername());
        $this->assertSame($request->getName(), $response->getUser()->getName());
        $this->assertTrue(password_verify($request->getPassword(), $response->getUser()->getPassword()));
    }

    public function testRegisterUsernameBlank() {
        $this->expectException(ValidationException::class);
        $request = new UserRegisterRequest();
        $request->setUsername('');
        $request->setName('admin');
        $request->setPassword('123');

        $this->userService->register($request);
    }

    public function testRegisterNameBlank() {
        $this->expectException(ValidationException::class);
        $request = new UserRegisterRequest();
        $request->setUsername('admin');
        $request->setName('');
        $request->setPassword('123');

        $this->userService->register($request);
    }

    public function testRegisterPasswordBlank() {
        $this->expectException(ValidationException::class);
        $request = new UserRegisterRequest();
        $request->setUsername('admin');
        $request->setName('admin');
        $request->setPassword('');

        $this->userService->register($request);
    }

    public function testRegisterDuplicate() {
        $this->expectException(ValidationException::class);
        $request = new UserRegisterRequest();
        $request->setUsername('admin');
        $request->setName('admin');
        $request->setPassword('123');

        $this->userService->register($request);
        $this->userService->register($request);
    }

    public function testLoginSuccess() {
        $register = new UserRegisterRequest();
        $register->setUsername('admin');
        $register->setName('admin');
        $register->setPassword('123');
        $this->userService->register($register);

        $login = new UserLoginRequest();
        $login->setUsername('admin');
        $login->setPassword('123');
        $response = $this->userService->login($login);

        $this->assertSame($register->getUsername(), $response->getUser()->getUsername());
        $this->assertSame($register->getName(), $response->getUser()->getName());
        $this->assertTrue(password_verify($register->getPassword(), $response->getUser()->getPassword()));
    }

    public function testLoginUsernameBlank() {
        $this->expectException(ValidationException::class);
        $register = new UserRegisterRequest();
        $register->setUsername('admin');
        $register->setName('admin');
        $register->setPassword('123');
        $this->userService->register($register);

        $login = new UserLoginRequest();
        $login->setUsername('');
        $login->setPassword('123');
        $response = $this->userService->login($login);

        $this->assertSame($register->getUsername(), $response->getUser()->getUsername());
        $this->assertSame($register->getName(), $response->getUser()->getName());
        $this->assertTrue(password_verify($register->getPassword(), $response->getUser()->getPassword()));
    }

    public function testLoginPasswordBlank() {
        $this->expectException(ValidationException::class);
        $register = new UserRegisterRequest();
        $register->setUsername('admin');
        $register->setName('admin');
        $register->setPassword('123');
        $this->userService->register($register);

        $login = new UserLoginRequest();
        $login->setUsername('admin');
        $login->setPassword('');
        $response = $this->userService->login($login);

        $this->assertSame($register->getUsername(), $response->getUser()->getUsername());
        $this->assertSame($register->getName(), $response->getUser()->getName());
        $this->assertTrue(password_verify($register->getPassword(), $response->getUser()->getPassword()));
    }

    public function testLoginUserNotExist() {
        $this->expectException(ValidationException::class);
        $register = new UserRegisterRequest();
        $register->setUsername('admin');
        $register->setName('admin');
        $register->setPassword('123');
        $this->userService->register($register);

        $login = new UserLoginRequest();
        $login->setUsername('unknown');
        $login->setPassword('123');
        $response = $this->userService->login($login);

        $this->assertSame($register->getUsername(), $response->getUser()->getUsername());
        $this->assertSame($register->getName(), $response->getUser()->getName());
        $this->assertTrue(password_verify($register->getPassword(), $response->getUser()->getPassword()));
    }

    public function testLoginWrongPassword() {
        $this->expectException(ValidationException::class);
        $register = new UserRegisterRequest();
        $register->setUsername('admin');
        $register->setName('admin');
        $register->setPassword('123');
        $this->userService->register($register);

        $login = new UserLoginRequest();
        $login->setUsername('admin');
        $login->setPassword('unknown');
        $response = $this->userService->login($login);

        $this->assertSame($register->getUsername(), $response->getUser()->getUsername());
        $this->assertSame($register->getName(), $response->getUser()->getName());
        $this->assertTrue(password_verify($register->getPassword(), $response->getUser()->getPassword()));
    }

    public function testChangeNameSuccess() {
        $register = new UserRegisterRequest();
        $register->setUsername('admin');
        $register->setName('admin');
        $register->setPassword('123');
        $this->userService->register($register);

        $session = new Session();
        $session->setId(uniqid());
        $session->setUserUsername($register->getUsername());
        $this->sessionRepository->save($session);
        $_COOKIE['X-LOG-SESSION'] = $session->getId();

        $request = new UserCnRequest();
        $request->setName('bot-x');
        $response = $this->userService->changeName($request);

        $this->assertNotSame($register->getName(), $response->getUser()->getName());
    }

    public function testChangeNameBlank() {
        $this->expectException(ValidationException::class);
        $register = new UserRegisterRequest();
        $register->setUsername('admin');
        $register->setName('admin');
        $register->setPassword('123');
        $this->userService->register($register);

        $session = new Session();
        $session->setId(uniqid());
        $session->setUserUsername($register->getUsername());
        $this->sessionRepository->save($session);
        $_COOKIE['X-LOG-SESSION'] = $session->getId();

        $request = new UserCnRequest();
        $request->setName('');
        $response = $this->userService->changeName($request);

        $this->assertNull($response);
    }

    public function testChangePasswordSuccess() {
        $register = new UserRegisterRequest();
        $register->setUsername('admin');
        $register->setName('admin');
        $register->setPassword('123');
        $this->userService->register($register);

        $session = new Session();
        $session->setId(uniqid());
        $session->setUserUsername($register->getUsername());
        $this->sessionRepository->save($session);
        $_COOKIE['X-LOG-SESSION'] = $session->getId();

        $request = new UserCpRequest();
        $request->setOldPassword($register->getPassword());
        $request->setNewPassword('1234');
        $response = $this->userService->changePassword($request);

        $this->assertNotNull($response);
    }

    public function testChangePasswordBlank() {
        $this->expectException(ValidationException::class);
        $register = new UserRegisterRequest();
        $register->setUsername('admin');
        $register->setName('admin');
        $register->setPassword('123');
        $this->userService->register($register);

        $session = new Session();
        $session->setId(uniqid());
        $session->setUserUsername($register->getUsername());
        $this->sessionRepository->save($session);
        $_COOKIE['X-LOG-SESSION'] = $session->getId();

        $request = new UserCpRequest();
        $request->setOldPassword('');
        $request->setNewPassword('1234');
        $response = $this->userService->changePassword($request);

        $this->assertNull($response);
    }

    public function testChangePasswordWrongOldPassword() {
        $this->expectException(ValidationException::class);
        $register = new UserRegisterRequest();
        $register->setUsername('admin');
        $register->setName('admin');
        $register->setPassword('123');
        $this->userService->register($register);

        $session = new Session();
        $session->setId(uniqid());
        $session->setUserUsername($register->getUsername());
        $this->sessionRepository->save($session);
        $_COOKIE['X-LOG-SESSION'] = $session->getId();

        $request = new UserCpRequest();
        $request->setOldPassword('i dont know');
        $request->setNewPassword('1234');
        $response = $this->userService->changePassword($request);

        $this->assertNull($response);
    }
}