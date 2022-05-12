<?php

namespace Web\InterChat\Middleware;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Service\SessionService;
use Web\InterChat\Util\View;

class MustLogin {

    public function before() {
        $sessionRepository = new SessionRepository(Database::getConnection('app'));
        $userRepository = new UserRepository(Database::getConnection('app'));
        $sessionService = new SessionService($sessionRepository, $userRepository);

        $user = $sessionService->current();
        if($user == null) {
            View::redirect('/user/login');
        } 
    }
}