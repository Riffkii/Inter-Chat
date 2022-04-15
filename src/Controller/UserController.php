<?php

namespace Web\InterChat\Controller;
use Web\InterChat\Util\View;
use Web\InterChat\Model\Request\UserRegisterRequest;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Service\UserService;
use Web\InterChat\Util\Database;
use Web\InterChat\Exception\ValidationException;
use Web\InterChat\Model\Request\UserLoginRequest;
use Web\InterChat\Service\SessionService;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Model\Request\UserCnRequest;
use Web\InterChat\Model\Request\UserCpRequest;

class UserController {

    private UserService $userService;
    private SessionService $sessionService;

    public function __construct() {
        $sessionRepository = new SessionRepository(Database::getConnection('app'));
        $userRepository = new UserRepository(Database::getConnection('app'));
        $this->userService = new UserService($userRepository, $sessionRepository);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
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
            $this->sessionService->create($request->getUsername());
            View::redirect('/');
        } catch (ValidationException $e) {
            View::render('login', [
                'title' => 'Login',
                'error' => $e->getMessage()
            ]);
        } 
    }

    public function logout() {
        $this->sessionService->destroy();
        View::redirect('/');
    }

    public function profile() {
        View::render('Profile', [
            'title' => 'Profile'
        ]);
    }

    public function changeName() {
        View::render('ChangeName', [
            'title' => 'Profile'
        ]);
    }

    public function postChangeName() {
        try {
            $request = new UserCnRequest();
            $request->setName($_POST['cn']);

            $this->userService->changeName($request);
            View::redirect('/');
        } catch (ValidationException $e) {
            View::render('ChangeName', [
                'title' => 'Profile',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function changePassword() {
        View::render('ChangePassword', [
            'title' => 'Profile'
        ]);
    }

    public function postChangePassword() {
        try {
            $request = new UserCpRequest();
            $request->setOldPassword($_POST['op']);
            $request->setNewPassword($_POST['np']);

            $this->userService->changePassword($request);
            View::redirect('/');
        } catch (ValidationException $e) {
            View::render('ChangePassword', [
                'title' => 'Profile',
                'error' => $e->getMessage()
            ]);
        }
    }
}