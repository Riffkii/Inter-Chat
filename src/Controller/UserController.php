<?php

namespace Web\InterChat\Controller;
use Web\InterChat\Util\View;
use Web\InterChat\Model\Request\UserRegisterRequest;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Service\UserService;
use Web\InterChat\Util\Database;
use Web\InterChat\Exception\ValidationException;
use Web\InterChat\Model\Request\UserLoginRequest;

class UserController {

    private UserRepository $userRepository;
    private UserService $userService;

    public function __construct() {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userService = new UserService($this->userRepository);
    }
    
    public function register() {
        View::render('Register', [
            'title' => 'Register'
        ]);
    }

    public function postRegister() {
        try {
            $request = new UserRegisterRequest();
            $request->setUsername($_POST['username']);
            $request->setName($_POST['name']);
            $request->setPassword($_POST['password']);

            $this->userService->register($request);
            View::redirect('/user/login');
        } catch (ValidationException $e) {
            View::render('register', [
                'title' => 'Register',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function login() {
        View::render('Login', [
            'title' => 'Login'
        ]);
    }

    public function postLogin() {
        try {
            $request = new UserLoginRequest();
            $request->setUsername($_POST['username']);
            $request->setPassword($_POST['password']);

            $this->userService->login($request);
            View::redirect('/');
        } catch (ValidationException $e) {
            View::render('login', [
                'title' => 'Login',
                'error' => $e->getMessage()
            ]);
        } 
    }
}