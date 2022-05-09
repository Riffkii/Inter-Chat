<?php

namespace Web\InterChat\Service;
use PHPUnit\Framework\TestCase;
use Web\InterChat\Util\Database;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Repository\FriendshipRepository;
use Web\InterChat\Repository\NotificationRepository;
use Web\InterChat\Model\Database\User;
use Web\InterChat\Model\Request\AddNotificationRequest;
use Error;
use Web\InterChat\Model\Database\Session;

class NotificationServiceTest extends TestCase {
    
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    private NotificationRepository $notificationRepository;
    private NotificationService $notificationService;

    protected function setUp(): void {
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $sessionService = new SessionService($this->sessionRepository, $userRepository);
        $friendshipRepository = new FriendshipRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->notificationRepository = new NotificationRepository(Database::getConnection());
        $this->notificationService = new NotificationService($this->notificationRepository, $sessionService);

        $this->sessionRepository->deleteAll();
        $friendshipRepository->deleteAll();
        $this->notificationRepository->deleteAll();
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

    public function testAddNotificationSuccess() {
        $this->registerHelper('admin', 'admin13', '123');
        $this->registerHelper('joko', 'joko13', '123');

        $request = new AddNotificationRequest();
        $request->setUsername('joko');
        $request->setMessageFrom('admin');
        $request->setMessage('test');
        $response = $this->notificationService->addNotification($request);

        $data = $this->notificationRepository->getMessageByMFAndUsername($response->getNotification()->getMessageFrom(), $response->getNotification()->getUsername());

        $this->assertSame(1, sizeof($data));
    }

    public function testAddNotificationFailed() {
        $this->expectException(Error::class);
        $this->registerHelper('admin', 'admin13', '123');
        $this->registerHelper('joko', 'joko13', '123');

        $request = new AddNotificationRequest();
        $request->setUsername(null);
        $request->setMessageFrom('admin');
        $request->setMessage('test');
        $this->notificationService->addNotification($request);
    }

    public function testShowAllNotificationsSuccess() {
        $this->registerHelper('admin', 'admin13', '123');
        $sessionId = $this->registerHelper('joko', 'joko13', '123');

        $request = new AddNotificationRequest();
        $request->setUsername('joko');
        $request->setMessageFrom('admin');
        $request->setMessage('test 1');
        $this->notificationService->addNotification($request);

        $request->setMessage('test 2');
        $this->notificationService->addNotification($request);

        $_COOKIE['X-LOG-SESSION'] = $sessionId;
        $response = $this->notificationService->showAllNotifications();

        $this->assertSame(2, sizeof($response));
        $this->assertSame('test 1', $response[0]->getMessage());
        $this->assertSame('test 2', $response[1]->getMessage());
    }

    public function testShowAllNotificationsFailed() {
        $this->expectException(Error::class);
        $_COOKIE['X-LOG-SESSION'] = "unknown";
        $response = $this->notificationService->showAllNotifications();

        $this->assertSame(2, sizeof($response));
    }

    public function testDeleteNotificationSuccess() {
        $this->registerHelper('admin', 'admin13', '123');
        $sessionId = $this->registerHelper('joko', 'joko13', '123');

        $request = new AddNotificationRequest();
        $request->setUsername('joko');
        $request->setMessageFrom('admin');
        $request->setMessage('test 1');
        $this->notificationService->addNotification($request);

        $request->setMessage('test 2');
        $response = $this->notificationService->addNotification($request);

        $this->notificationService->deleteNotification($response->getNotification()->getID());
        
        $_COOKIE['X-LOG-SESSION'] = $sessionId;
        $response = $this->notificationService->showAllNotifications();

        $this->assertSame(1, sizeof($response));
    }

    public function testDeleteNotificationFailed() {
        $this->registerHelper('admin', 'admin13', '123');
        $sessionId = $this->registerHelper('joko', 'joko13', '123');

        $request = new AddNotificationRequest();
        $request->setUsername('joko');
        $request->setMessageFrom('admin');
        $request->setMessage('test 1');
        $this->notificationService->addNotification($request);

        $request->setMessage('test 2');
        $response = $this->notificationService->addNotification($request);

        $this->notificationService->deleteNotification(50000000000);

        $_COOKIE['X-LOG-SESSION'] = $sessionId;
        $response = $this->notificationService->showAllNotifications();

        $this->assertSame(2, sizeof($response));
    }
}