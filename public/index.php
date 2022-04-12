<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Web\InterChat\Util\Router;
use Web\InterChat\Controller\HomeController;
use Web\InterChat\Controller\UserController;

Router::add('GET', '/', HomeController::class, 'index');
Router::add('GET', '/user/login', UserController::class, 'login');
Router::add('POST', '/user/login', UserController::class, 'postLogin');
Router::add('GET', '/user/register', UserController::class, 'register');
Router::add('POST', '/user/register', UserController::class, 'postRegister');

Router::run();