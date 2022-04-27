<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Web\InterChat\Util\Router;
use Web\InterChat\Controller\HomeController;
use Web\InterChat\Controller\UserController;
use Web\InterChat\Middleware\MustNotLogin;
use Web\InterChat\Middleware\MustLogin;
use Web\InterChat\Controller\FriendshipController;

Router::add('GET', '/', HomeController::class, 'index');
Router::add('GET', '/user/login', UserController::class, 'login', [MustNotLogin::class]);
Router::add('POST', '/user/login', UserController::class, 'postLogin', [MustNotLogin::class]);
Router::add('GET', '/user/register', UserController::class, 'register', [MustNotLogin::class]);
Router::add('POST', '/user/register', UserController::class, 'postRegister', [MustNotLogin::class]);
Router::add('GET', '/user/logout', UserController::class, 'logout', [MustLogin::class]);
Router::add('GET', '/user/profile', UserController::class, 'profile', [MustLogin::class]);
Router::add('GET', '/user/cn', UserController::class, 'changeName', [MustLogin::class]);
Router::add('POST', '/user/cn', UserController::class, 'postChangeName', [MustLogin::class]);
Router::add('GET', '/user/cp', UserController::class, 'changePassword', [MustLogin::class]);
Router::add('POST', '/user/cp', UserController::class, 'postChangePassword', [MustLogin::class]);
Router::add('GET', '/user/find-friend', FriendshipController::class, 'findFriend', [MustLogin::class]);
Router::add('POST', '/user/find-friend', FriendshipController::class, 'postFindFriend', [MustLogin::class]);
Router::add('POST', '/user/add-friend', FriendshipController::class, 'postAddFriend', [MustLogin::class]);

Router::run();