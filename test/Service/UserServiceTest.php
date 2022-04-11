<?php

namespace Web\InterChat\Service;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Request\UserRegisterRequest;
use PHPUnit\Framework\TestCase;
use Web\InterChat\Exception\ValidationException;

class UserServiceTest extends TestCase{

    private UserRepository $userRepository;
    private UserService $userService;

    protected function setUp(): void {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userService = new UserService($this->userRepository);

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

        $response = $this->userService->register($request);
    }

    public function testRegisterNameBlank() {
        $this->expectException(ValidationException::class);
        $request = new UserRegisterRequest();
        $request->setUsername('admin');
        $request->setName('');
        $request->setPassword('123');

        $response = $this->userService->register($request);
    }

    public function testRegisterPasswordBlank() {
        $this->expectException(ValidationException::class);
        $request = new UserRegisterRequest();
        $request->setUsername('admin');
        $request->setName('admin');
        $request->setPassword('');

        $response = $this->userService->register($request);
    }

    public function testRegisterExist() {
        $this->expectException(ValidationException::class);
        $request = new UserRegisterRequest();
        $request->setUsername('admin');
        $request->setName('admin');
        $request->setPassword('123');

        $response = $this->userService->register($request);
        $response = $this->userService->register($request);
    }
}