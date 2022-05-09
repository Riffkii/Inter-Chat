<?php

namespace Web\InterChat\Controller;
require_once __DIR__ . "/../Helper/Helper.php";
use PHPUnit\Framework\TestCase;
use Web\InterChat\Repository\FriendshipRepository;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Service\FriendshipService;
use Web\InterChat\Service\SessionService;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Model\Database\User;
use Web\InterChat\Model\Database\Session;
use Web\InterChat\Model\Database\Friendship;

class FriendshipControllerTest extends TestCase {

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    private SessionService $sessionService;
    private FriendshipRepository $friendshipRepository;
    private FriendshipController $friendshipsController;

    protected function setUp(): void {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->friendshipRepository = new FriendshipRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);
        $friendshipService = new FriendshipService($this->friendshipRepository, $this->sessionRepository);
        $this->friendshipsController = new FriendshipController();

        $this->sessionRepository->deleteAll();
        $this->friendshipRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    private function registerHelper(string $username, string $name, string $password): string {
        $user = new User();
        $user->setUsername($username);
        $user->setName($name);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $this->userRepository->save($user);

        $session = new Session();
        $session->setId(uniqid());
        $session->setUserUsername($username);
        $this->sessionRepository->save($session);

        return $session->getId();
    }

    public function testSearchFriend() {
        $sessionId = $this->registerHelper("joko", "joko13", "123");
        $this->registerHelper("rudi", "rudi13", "123");
        $this->registerHelper("imron", "imron13", "123");

        $friendship = new Friendship();
        $friendship->setUser1Username("IMron");
        $friendship->setUser2Username("jOko");
        $this->friendshipRepository->save($friendship);

        $friendship = new Friendship();
        $friendship->setUser1Username("imron");
        $friendship->setUser2Username("rudi");
        $this->friendshipRepository->save($friendship);

        $_COOKIE['X-LOG-SESSION'] = $sessionId;
        $_GET['friend'] = 'imron';
        $response = $this->friendshipsController->searchFriend();

        $this->assertSame("imron13", json_decode($response)[0]->name);
    }
}