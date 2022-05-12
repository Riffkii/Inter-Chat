<?php

namespace Web\InterChat\Service;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Repository\FriendshipRepository;
use Web\InterChat\Repository\NotificationRepository;
use Web\InterChat\Model\Database\User;
use Web\InterChat\Repository\ChatRepository;
use Web\InterChat\Model\Request\SendMessageRequest;
use Web\InterChat\Model\Database\Session;
use PHPUnit\Framework\TestCase;
use Web\InterChat\Exception\ValidationException;
use Exception;
use Web\InterChat\Model\Request\LoadMessageRequest;

class ChatServiceTest extends TestCase{

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    private ChatRepository $chatRepository;
    private ChatService $chatService;

    protected function setUp(): void {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $friendshipRepository = new FriendshipRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $notificationRepository = new NotificationRepository(Database::getConnection());
        $this->chatRepository = new ChatRepository(Database::getConnection());
        $this->chatService = new ChatService($this->chatRepository, $this->sessionRepository);

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

    public function testSendMessageSuccess() {
        $sessionId = $this->registerHelper('joko', 'joko13', '123');
        $this->registerHelper('dedy', 'dedy13', '123');

        $_COOKIE['X-LOG-SESSION'] = $sessionId;
        $request = new SendMessageRequest();
        $request->setMessage('halo');
        $request->setTargetUser('dedy');
        $response = $this->chatService->sendMessage($request);

        $this->assertSame('joko', $response->getChat()->getFromUser());
        $this->assertSame($request->getMessage(), $response->getChat()->getMessage());
        $this->assertSame($request->getTargetUser(), $response->getChat()->getTargetUser());
    }

    public function testSendMessageValidationError() {
        $this->expectException(ValidationException::class);
        $sessionId = $this->registerHelper('joko', 'joko13', '123');
        $this->registerHelper('dedy', 'dedy13', '123');

        $_COOKIE['X-LOG-SESSION'] = $sessionId;
        $request = new SendMessageRequest();
        $request->setMessage('    ');
        $request->setTargetUser('dedy');
        $this->chatService->sendMessage($request);
    }

    public function testSendMessageFailed() {
        $this->expectException(Exception::class);
        $sessionId = $this->registerHelper('joko', 'joko13', '123');
        $this->registerHelper('dedy', 'dedy13', '123');

        $_COOKIE['X-LOG-SESSION'] = $sessionId;
        $request = new SendMessageRequest();
        $request->setMessage('halo');
        $request->setTargetUser('dodi');
        $this->chatService->sendMessage($request);
    }

    public function testLoadMessageSuccess() {
        $sessionId = $this->registerHelper('joko', 'joko13', '123');
        $this->registerHelper('dedy', 'dedy13', '123');

        $_COOKIE['X-LOG-SESSION'] = $sessionId;
        $request = new SendMessageRequest();
        $request->setMessage('halo');
        $request->setTargetUser('dedy');
        $this->chatService->sendMessage($request);

        $request = new LoadMessageRequest();
        $request->setTargetUser('dedy');
        $response = $this->chatService->loadMessage($request);

        $this->assertSame(1, sizeof($response->getChats()));
        $this->assertSame("halo", $response->getChats()[0]->getMessage());
    }

    public function testLoadMessageFailed() {
        $sessionId = $this->registerHelper('joko', 'joko13', '123');

        $_COOKIE['X-LOG-SESSION'] = $sessionId;
        $request = new LoadMessageRequest();
        $request->setTargetUser('dedy');
        $response = $this->chatService->loadMessage($request);

        $this->assertSame(0, sizeof($response->getChats()));
    }
}