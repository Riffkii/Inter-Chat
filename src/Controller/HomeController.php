<?php

namespace Web\InterChat\Controller;
use Web\InterChat\Util\View;

class HomeController {
    
    public function index() {
        View::render('Home');
    }
}