<?php

namespace Web\InterChat\Controller;
use Web\InterChat\Util\View;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Service\SessionService;
use Web\InterChat\Util\Database;

class HomeController {

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    private SessionService $sessionService;

    public function __construct() {
        $this->sessionRepository = new SessionRepository(Database::getConnection('app'));
        $this->userRepository = new UserRepository(Database::getConnection('app'));
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);
    }
    
    public function index() {
        $user = $this->sessionService->current();
        if($user != null) {
            View::render('Dashboard', [
                'name' => $user->getName()
            ]);
        } else {
            View::render('Home', []);
        }
    }
}