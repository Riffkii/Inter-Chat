<?php

namespace Web\InterChat\Repository;
use PHPUnit\Framework\TestCase;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Database\User;
use PDOException;

class UserRepositoryTest extends TestCase {

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }
    
    public function testSaveAndFindByUsernameSuccess() {
        $user = new User();
        $user->setUsername('admin');
        $user->setName('admin');
        $user->setPassword('123');

        $this->userRepository->save($user);

        $result = $this->userRepository->findByUsername('admin');

        $this->assertSame($user->getUsername(), $result->getUsername());
        $this->assertSame($user->getName(), $result->getName());
        $this->assertSame($user->getPassword(), $result->getPassword());
    }

    public function testSaveFailed() {
        $this->expectException(PDOException::class);
        $user = new User();
        $user->setUsername('admin');
        $user->setName('admin');
        $user->setPassword('123');

        $this->userRepository->save($user);
        $this->userRepository->save($user);
    }

    public function testFindByUsernameFailed() {
        $result = $this->userRepository->findByUsername("unknown");
        $this->assertNull($result);
    }

    public function testUpdate() {
        $user = new User();
        $user->setUsername('1');
        $user->setName('joko');
        $user->setPassword('123');
        $this->userRepository->save($user);

        $user->setUsername('2');
        $user->setName('budi');
        $this->userRepository->save($user);

        $request = new User();
        $request->setUsername('1');
        $request->setName('bot');
        $request->setPassword('123');
        $this->userRepository->update($request);

        $request->setUsername('2');
        $request->setName('sky');
        $request->setPassword('gtw');
        $this->userRepository->update($request);

        $response = $this->userRepository->findByUsername($request->getUsername());

        $this->assertSame($request->getUsername(), $response->getUsername());
        $this->assertSame($request->getName(), $response->getName());
        $this->assertSame($request->getPassword(), $response->getPassword());
    }
}