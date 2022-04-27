<?php

namespace Web\InterChat\Repository;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Database\Friendship;
use PHPUnit\Framework\TestCase;
use Web\InterChat\Model\Database\User;
use PDOException;

class FriendshipRepositoryTest extends TestCase{

    private UserRepository $userRepository;
    private FriendshipRepository $friendshipRepository;

    protected function setUp(): void {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->friendshipRepository = new FriendshipRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());

        $sessionRepository->deleteAll();
        $this->friendshipRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    private function registerHelper(string $username, string $name, string $password) {
        $user = new User();
        $user->setUsername($username);
        $user->setName($name);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));

        $this->userRepository->save($user);
    }

    public function testSaveSuccess() {
        $this->registerHelper("udin", "udin13", "123");
        $this->registerHelper("JOKO", "joko13", "123");

        $friendship = new Friendship();
        $friendship->setUser1Username("Udin");
        $friendship->setUser2Username("jOko");

        $this->friendshipRepository->save($friendship);

        $result = $this->friendshipRepository->findFriendsByUsername("UDIN");

        $this->assertSame("joko", $result[0]->getUsername());
    }

    public function testSaveFailed() {
        $this->expectException(PDOException::class);

        $this->registerHelper("udin", "udin13", "123");
        $this->registerHelper("joko", "joko13", "123");

        $friendship = new Friendship();
        $friendship->setUser1Username("Udin");
        $friendship->setUser2Username("Joko");
        $this->friendshipRepository->save($friendship);

        $friendship = new Friendship();
        $friendship->setUser1Username("joko");
        $friendship->setUser2Username("udin");
        $this->friendshipRepository->save($friendship);
    }

    public function testFindFriendsByUsernameSuccess() {
        $this->registerHelper("udin", "udin13", "123");
        $this->registerHelper("joko", "joko13", "123");
        $this->registerHelper("tejo", "tejo13", "123");
        $this->registerHelper("farel", "farel13", "123");

        $friendship = new Friendship();
        $friendship->setUser1Username("Udin");
        $friendship->setUser2Username("Joko");
        $this->friendshipRepository->save($friendship);

        $friendship = new Friendship();
        $friendship->setUser1Username("uDin");
        $friendship->setUser2Username("Tejo");
        $this->friendshipRepository->save($friendship);

        $friendship = new Friendship();
        $friendship->setUser1Username("tejo");
        $friendship->setUser2Username("joko");
        $this->friendshipRepository->save($friendship);

        $friendship = new Friendship();
        $friendship->setUser1Username("fArel");
        $friendship->setUser2Username("Joko");
        $this->friendshipRepository->save($friendship);

        $result = $this->friendshipRepository->findFriendsByUsername("joko");

        $this->assertSame("farel", $result[0]->getUsername());
        $this->assertSame("tejo", $result[1]->getUsername());
        $this->assertSame("udin", $result[2]->getUsername());
        $this->assertSame(3, sizeof($result));
    }

    public function testFindFriendsByUsernameFailed() {
        $this->registerHelper("udin", "udin13", "123");
        $result = $this->friendshipRepository->findFriendsByUsername("udin");
        $this->assertSame(0, sizeof($result));
    }

    public function testFindNotFriendsByUsernameSuccess() {
        $this->registerHelper("udin", "udin13", "123");
        $this->registerHelper("joko", "joko13", "123");
        $this->registerHelper("tejo", "tejo13", "123");
        $this->registerHelper("farel", "farel13", "123");

        $friendship = new Friendship();
        $friendship->setUser1Username("Udin");
        $friendship->setUser2Username("Joko");
        $this->friendshipRepository->save($friendship);

        $friendship = new Friendship();
        $friendship->setUser1Username("udIn");
        $friendship->setUser2Username("tEjo");
        $this->friendshipRepository->save($friendship);

        $friendship = new Friendship();
        $friendship->setUser1Username("Tejo");
        $friendship->setUser2Username("Joko");
        $this->friendshipRepository->save($friendship);

        $friendship = new Friendship();
        $friendship->setUser1Username("Farel");
        $friendship->setUser2Username("joko");
        $this->friendshipRepository->save($friendship);

        $result = $this->friendshipRepository->findNotFriendsByUsername("farel");

        $this->assertSame("tejo", $result[0]->getUsername());
        $this->assertSame("udin", $result[1]->getUsername());
        $this->assertSame(2, sizeof($result));
    }

    public function testFindNotFriendsByUsernameFailed() {
        $this->registerHelper("UDIN", "udin13", "123");
        $this->registerHelper("jokO", "udin13", "123");

        $friendship = new Friendship();
        $friendship->setUser1Username("UDin");
        $friendship->setUser2Username("joko");
        $this->friendshipRepository->save($friendship);

        $result = $this->friendshipRepository->findNotFriendsByUsername("udin");
        $this->assertSame(0, sizeof($result));
    }

    public function testFindNotFriendByUsernameSuccess() {
        $this->registerHelper("UDIN", "udin13", "123");
        $this->registerHelper("tara", "tara13", "123");
        $this->registerHelper("jokO", "udin13", "123");
        $this->registerHelper("jokOwi", "jokowi13", "123");
        $this->registerHelper("susan", "susan13", "123");

        $friendship = new Friendship();
        $friendship->setUser1Username("tara");
        $friendship->setUser2Username("udiN");
        $this->friendshipRepository->save($friendship);

        $friendship->setUser1Username("tara");
        $friendship->setUser2Username("JOko");
        $result = $this->friendshipRepository->findNotFriendByUsername($friendship);

        $this->assertSame(2, sizeof($result));
        $this->assertSame("joko", $result[0]->getUsername());
        $this->assertSame("jokowi", $result[1]->getUsername());
    }

    public function testDeleteByUserSuccess() {
        $this->registerHelper("udin", "udin13", "123");
        $this->registerHelper("joko", "joko13", "123");

        $friendship = new Friendship();
        $friendship->setUser1Username("Udin");
        $friendship->setUser2Username("Joko");
        $this->friendshipRepository->save($friendship);

        $result = $this->friendshipRepository->findFriendsByUsername("joko");
        $this->assertSame(1, sizeof($result));

        $this->friendshipRepository->deleteFriendsByUsername($friendship);

        $result1 = $this->friendshipRepository->findFriendsByUsername("joko");
        $result2 = $this->friendshipRepository->findFriendsByUsername("udin");

        $this->assertSame(0, sizeof($result1));
        $this->assertSame(0, sizeof($result2));
    }

    public function testDeleteByUserFailed() {
        $this->registerHelper("udin", "udin13", "123");
        $this->registerHelper("joko", "joko13", "123");

        $friendship = new Friendship();
        $friendship->setUser1Username("Udin");
        $friendship->setUser2Username("Joko");
        $this->friendshipRepository->save($friendship);

        $friendship->setUser1Username("budi");
        $friendship->setUser2Username("Joko");
        $this->friendshipRepository->deleteFriendsByUsername($friendship);

        $result = $this->friendshipRepository->findFriendsByUsername("joko");
        $this->assertSame(1, sizeof($result));
    }
}