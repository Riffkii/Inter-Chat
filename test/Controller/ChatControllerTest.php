<?php

namespace Web\InterChat\Controller;
use PHPUnit\Framework\TestCase;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Repository\FriendshipRepository;
use Web\InterChat\Repository\NotificationRepository;
use Web\InterChat\Repository\ChatRepository;
use Web\InterChat\Service\ChatService;
use Web\InterChat\Model\Database\User;
use Web\InterChat\Model\Database\Session;
use Web\InterChat\Model\Request\SendMessageRequest;

class ChatControllerTest extends TestCase {

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    private ChatRepository $chatRepository;
    private ChatService $chatService;
    private ChatController $chatController;

    protected function setUp(): void {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $friendshipRepository = new FriendshipRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $notificationRepository = new NotificationRepository(Database::getConnection());
        $this->chatRepository = new ChatRepository(Database::getConnection());
        $this->chatService = new ChatService($this->chatRepository, $this->sessionRepository);
        $this->chatController = new ChatController();

        $this->chatRepository->deleteAll();
        $this->sessionRepository->deleteAll();
        $friendshipRepository->deleteAll();
        $notificationRepository->deleteAll();
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

    public function testLoad() {
        $sessionId = $this->registerHelper('joko', 'joko13', '123');
        $this->registerHelper('dedy', 'dedy13', '123');

        $_COOKIE['X-LOG-SESSION'] = $sessionId;
        $request = new SendMessageRequest();
        $request->setMessage('halo');
        $request->setTargetUser('dedy');
        $this->chatService->sendMessage($request);
        $_GET['target'] = 'dedy';

        $this->chatController->message();
        $this->expectOutputRegex('[aswka]');
    }
}