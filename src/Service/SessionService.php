<?php

namespace Web\InterChat\Service;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Model\Database\Session;
use Web\InterChat\Model\Database\User;
use Web\InterChat\Repository\UserRepository;

class SessionService {

    private static string $COOKIE_NAME = 'X-LOG-SESSION';

    public function __construct(private SessionRepository $sessionRepository,
                                private UserRepository $userRepository) {}

    public function create(string $userUsername): Session {
        $session = new Session();
        $session->setId(uniqid());
        $session->setUserUsername($userUsername);

        $this->sessionRepository->save($session);
        setcookie(self::$COOKIE_NAME, $session->getId(), time() + (60 * 60 * 24), '/');

        return $session;
    }

    public function destroy() {
        $sessionId = $_COOKIE[self::$COOKIE_NAME];
        $this->sessionRepository->deleteById($sessionId);
        setcookie(self::$COOKIE_NAME, '', 1, '/');
    }

    public function current(): ?User {
        if(isset($_COOKIE[self::$COOKIE_NAME])) {
            $sessionId = $_COOKIE[self::$COOKIE_NAME];
            $result = $this->sessionRepository->findById($sessionId);
            return $this->userRepository->findByUsername($result?->getUserUsername());
        }
        return null;
    }
}