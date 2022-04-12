<?php

namespace Web\InterChat\Controller;
use Web\InterChat\Util\View;

class UserController {
    
    public function register() {
        View::render('register');
    }

    public function login() {
        View::render('login');
    }
}