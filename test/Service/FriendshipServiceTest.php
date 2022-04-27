<?php

namespace Web\InterChat\Service;
use PHPUnit\Framework\TestCase;
use Web\InterChat\Repository\FriendshipRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Model\Database\User;
use Web\InterChat\Model\Database\Session;
use Web\InterChat\Model\Request\AddFriendRequest;
use Exception;
use Web\InterChat\Model\Database\Friendship;
use Error;
use Web\InterChat\Model\Request\UnfriendRequest;
use Web\InterChat\Model\Request\FindNotFriendRequest;

class FriendshipServiceTest extends TestCase {

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    private FriendshipRepository $friendshipRepository;
    private FriendshipService $friendshipService;

    protected function setUp(): void {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->friendshipRepository = new FriendshipRepository(Database::getConnection());
        $this->friendshipService = new FriendshipService($this->friendshipRepository, $this->sessionRepository);

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

    public function testAddFriendSuccess() {
        $this->registerHelper("joko", "joko13", "123");
        $sessionId = $this->registerHelper("susan", "susan13", "123");
        $_COOKIE['X-LOG-SESSION'] = $sessionId;

        $request = new AddFriendRequest();
        $request->setToUser("joKo");
        
        $response = $this->friendshipService->addFriend($request);
        $res = $this->friendshipService->showFriends();

        $this->assertSame("joko", $res[0]);
        $this->assertSame("susan", $response->getFriendship()->getUser2Username());
    }

    public function testAddFriendFailed() {
        $this->expectException(Exception::class);

        $this->registerHelper("joko", "joko13", "123");
        $sessionId = $this->registerHelper("susan", "susan13", "123");
        $_COOKIE['X-LOG-SESSION'] = $sessionId;

        $request = new AddFriendRequest();
        $request->setToUser("joKo");
        
        $this->friendshipService->addFriend($request);
        $this->friendshipService->addFriend($request);
    }

    public function testFindNotFriendSuccess() {
        $sessionId = $this->registerHelper("joko", "joko13", "123");
        $this->registerHelper("rudi", "rudi13", "123");
        $this->registerHelper("imron", "imron13", "123");

        $friendship = new Friendship();
        $friendship->setUser1Username("rudi");
        $friendship->setUser2Username("jOko");
        $this->friendshipRepository->save($friendship);

        $friendship = new Friendship();
        $friendship->setUser1Username("imron");
        $friendship->setUser2Username("rudi");
        $this->friendshipRepository->save($friendship);

        $_COOKIE['X-LOG-SESSION'] = $sessionId;
        $request = new FindNotFriendRequest();
        $request->setToUser("imron");
        $response = $this->friendshipService->findNotFriend($request);

        $this->assertSame("imron", $response->getNotFriends()[0]->getUsername());
        $this->assertSame(1, sizeof($response->getNotFriends()));
    }

    public function testFindNotFriendFailed() {
        $this->expectException(Error::class);
        $_COOKIE['X-LOG-SESSION'] = "lucha";
        $request = new FindNotFriendRequest();
        $request->setToUser("imron");
        $response = $this->friendshipService->findNotFriend($request);
    }

    public function testShowFriendsSuccess() {
        $this->registerHelper("joko", "joko13", "123");
        $sessionId = $this->registerHelper("rudi", "rudi13", "123");
        $this->registerHelper("imron", "imron13", "123");

        $friendship = new Friendship();
        $friendship->setUser1Username("rudi");
        $friendship->setUser2Username("jOko");
        $this->friendshipRepository->save($friendship);

        $friendship = new Friendship();
        $friendship->setUser1Username("imron");
        $friendship->setUser2Username("rudi");
        $this->friendshipRepository->save($friendship);

        $_COOKIE['X-LOG-SESSION'] = $sessionId;
        $response = $this->friendshipService->showFriends();

        $this->assertSame("imron", $response[0]);
        $this->assertSame("joko", $response[1]);
        $this->assertSame(2, sizeof($response));
    }

    public function testShowFriendsFailed() {
        $this->expectException(Error::class);
        $_COOKIE['X-LOG-SESSION'] = "unknown";
        $this->friendshipService->showFriends();
    }

    public function testUnfriendSuccess() {
        $this->registerHelper("joko", "joko13", "123");
        $this->registerHelper("jokowi", "jokowi13", "123");
        $sessionId = $this->registerHelper("rudi", "rudi13", "123");
        $this->registerHelper("imron", "imron13", "123");

        $friendship = new Friendship();
        $friendship->setUser1Username("rudi");
        $friendship->setUser2Username("jOko");
        $this->friendshipRepository->save($friendship);

        $friendship = new Friendship();
        $friendship->setUser1Username("Rudi");
        $friendship->setUser2Username("jOkowi");
        $this->friendshipRepository->save($friendship);

        $friendship = new Friendship();
        $friendship->setUser1Username("joko");
        $friendship->setUser2Username("iMron");
        $this->friendshipRepository->save($friendship);

        $_COOKIE['X-LOG-SESSION'] = $sessionId;

        $request = new UnfriendRequest();
        $request->setToUser("JOKO");
        $this->friendshipService->unfriend($request);
        $request->setToUser("JOKOwi");
        $this->friendshipService->unfriend($request);

        $request = new FindNotFriendRequest();
        $request->setToUser("jokO");
        $response = $this->friendshipService->findNotFriend($request);

        $this->assertSame("joko", $response->getNotFriends()[0]);
        $this->assertSame("jokowi", $response->getNotFriends()[1]);
        $this->assertSame(2, sizeof($response->getNotFriends()));
    }

    public function testUnfriendFailed() {
        $this->expectException(Error::class);
        $_COOKIE['X-LOG-SESSION'] = "unknown";
        
        $request = new UnfriendRequest();
        $request->setToUser(null);

        $this->friendshipService->unfriend($request);
    }
}