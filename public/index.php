<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Web\InterChat\Util\Router;
use Web\InterChat\Controller\HomeController;
use Web\InterChat\Controller\UserController;
use Web\InterChat\Middleware\MustNotLogin;
use Web\InterChat\Middleware\MustLogin;

Router::add('GET', '/', HomeController::class, 'index');
Router::add('GET', '/user/login', UserController::class, 'login', [MustNotLogin::class]);
Router::add('POST', '/user/login', UserController::class, 'postLogin', [MustNotLogin::class]);
Router::add('GET', '/user/register', UserController::class, 'register', [MustNotLogin::class]);
Router::add('POST', '/user/register', UserController::class, 'postRegister', [MustNotLogin::class]);
Router::add('GET', '/user/logout', UserController::class, 'logout', [MustLogin::class]);

Router::run();