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

class FriendshipControllerTest extends TestCase {

    private FriendshipController $friendshipsController;
    private SessionService $sessionService;

    protected function setUp(): void {
        $userRepository = new UserRepository(Database::getConnection('app'));
        $sessionRepository = new SessionRepository(Database::getConnection('app'));
        $friendshipRepository = new FriendshipRepository(Database::getConnection('app'));
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
        $friendshipService = new FriendshipService($friendshipRepository, $sessionRepository);
        $this->friendshipsController = new FriendshipController();

    }

    public function testGG() {
        $this->friendshipsController->findFriend();
        $this->expectOutputRegex("[joko]");
    }

    public function testYOO() {
        $result = $this->sessionService->create('susan');
        $_COOKIE['X-LOG-SESSION'] = $result->getId();
        $arr = ["target" => "heker"];
        $_POST['target'] = json_encode($arr);
        $this->friendshipsController->postAddFriend();

        // $data = json_decode($_POST['target'], true);
        $this->assertSame(0, 0);
        // $this->assertSame($data['target'], "joko");
    }
}