<?php

namespace Web\InterChat\Repository;
use PHPUnit\Framework\TestCase;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Database\User;
use Web\InterChat\Model\Database\Notification;
use Exception;

class NotificationRepositoryTest extends TestCase {

    private UserRepository $userRepository;
    private NotificationRepository $notificationRepository;

    protected function setUp(): void {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $friendshipRepository = new FriendshipRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->notificationRepository = new NotificationRepository(Database::getConnection());

        $sessionRepository->deleteAll();
        $friendshipRepository->deleteAll();
        $this->notificationRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    private function registerHelper(string $username, string $name, string $password) {
        $user = new User();
        $user->setUsername($username);
        $user->setName($name);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));

        $this->userRepository->save($user);
    }

    //GetMS(getMessagesByUsername) | GetM(getMessageByMFAndUsername)
    public function testSaveAndGetMSAndGetMSuccess() {
        $this->registerHelper('admin', 'admin', '123');
        $this->registerHelper('joko', 'joko', '123');

        $notification = new Notification();
        $notification->setUsername('joko');
        $notification->setMessageFrom('admin');
        $notification->setMessage('TEST');
        $this->notificationRepository->save($notification);

        $respone = $this->notificationRepository->getMessageByMFAndUsername('admin', 'joko');

        $this->assertSame($notification->getUsername(), $respone[0]->getUsername());
        $this->assertSame($notification->getMessageFrom(), $respone[0]->getMessageFrom());
        $this->assertSame($notification->getMessage(), $respone[0]->getMessage());
    }

    public function testSaveFailed() {
        $this->expectException(Exception::class);
        $this->registerHelper('admin', 'admin', '123');
        $this->registerHelper('joko', 'joko', '123');

        $notification = new Notification();
        $notification->setUsername('JoK');
        $notification->setMessageFrom('admin');
        $notification->setMessage('TEST');
        $this->notificationRepository->save($notification);
    }

    public function testGetMessagesByUsernameSuccess() {
        $this->registerHelper('admin', 'admin', '123');
        $this->registerHelper('joko', 'joko', '123');

        $notification = new Notification();
        $notification->setUsername('JoKo');
        $notification->setMessageFrom('admin');
        $notification->setMessage('TEST');
        $this->notificationRepository->save($notification);
        $this->notificationRepository->save($notification);

        $response = $this->notificationRepository->getMessagesByUsername($notification->getUsername());

        $this->assertSame(2, sizeof($response));
        $this->assertSame($response[1]->getUsername(), $notification->getUsername());
    }

    public function testGetMessagesByUsernameFailed() {
        $this->registerHelper('admin', 'admin', '123');
        $this->registerHelper('joko', 'joko', '123');

        $notification = new Notification();
        $notification->setUsername('JoKo');
        $notification->setMessageFrom('admin');
        $notification->setMessage('TEST');
        $this->notificationRepository->save($notification);
        $this->notificationRepository->save($notification);

        $response = $this->notificationRepository->getMessagesByUsername($notification->getMessageFrom());

        $this->assertSame(0, sizeof($response));
    }

    public function testGetMessageByMFSuccess() {
        $this->registerHelper('admin', 'admin', '123');
        $this->registerHelper('joko', 'joko', '123');

        $notification = new Notification();
        $notification->setUsername('JoKo');
        $notification->setMessageFrom('admin');
        $notification->setMessage('TEST');
        $this->notificationRepository->save($notification);
        $this->notificationRepository->save($notification);

        $response = $this->notificationRepository->getMessageByMF($notification->getMessageFrom());

        $this->assertSame(2, sizeof($response));
        $this->assertSame($response[1]->getUsername(), $notification->getUsername());
    }

    public function testGetMessageByMFFailed() {
        $this->registerHelper('admin', 'admin', '123');
        $this->registerHelper('joko', 'joko', '123');

        $notification = new Notification();
        $notification->setUsername('JoKo');
        $notification->setMessageFrom('admin');
        $notification->setMessage('TEST');
        $this->notificationRepository->save($notification);
        $this->notificationRepository->save($notification);

        $response = $this->notificationRepository->getMessageByMF($notification->getUsername());

        $this->assertSame(0, sizeof($response));
    }

    public function testGetMessageByMFAndUsernameFailed() {
        $this->registerHelper('admin', 'admin', '123');
        $this->registerHelper('joko', 'joko', '123');

        $notification = new Notification();
        $notification->setUsername('joko');
        $notification->setMessageFrom('admin');
        $notification->setMessage('TEST');
        $this->notificationRepository->save($notification);

        $respone = $this->notificationRepository->getMessageByMFAndUsername('unknown', 'joko');

        $this->assertSame(0, sizeof($respone));
    }

    public function testDeleteByIdSuccess() {
        $this->registerHelper('admin', 'admin', '123');
        $this->registerHelper('joko', 'joko', '123');

        $notification = new Notification();
        $notification->setUsername('joko');
        $notification->setMessageFrom('admin');
        $notification->setMessage('TEST 1');
        $this->notificationRepository->save($notification);

        $notification->setMessage('TEST 2');
        $this->notificationRepository->save($notification);

        $response = $this->notificationRepository->getMessageByMFAndUsername("admin", "joko");

        $this->notificationRepository->deleteById($response[0]->getId());

        $findResponse = $this->notificationRepository->getMessagesByUsername("joko");

        $this->assertSame(1, sizeof($findResponse));
    }

    public function testDeleteByIdFailed() {
        $this->registerHelper('admin', 'admin', '123');
        $this->registerHelper('joko', 'joko', '123');

        $notification = new Notification();
        $notification->setUsername('joko');
        $notification->setMessageFrom('admin');
        $notification->setMessage('TEST 1');
        $this->notificationRepository->save($notification);

        $this->notificationRepository->deleteById(50000000);

        $findResponse = $this->notificationRepository->getMessagesByUsername("joko");

        $this->assertSame(1, sizeof($findResponse));
    }
}