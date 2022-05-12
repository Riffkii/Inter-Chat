<?php

namespace Web\InterChat\Repository;
use PHPUnit\Framework\TestCase;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Database\User;
use Web\InterChat\Model\Database\Friendship;
use Web\InterChat\Model\Database\Chat;
use Exception;

class ChatRepositoryTest extends TestCase{

    private UserRepository $userRepository;
    private ChatRepository $chatRepository;

    protected function setUp(): void {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $friendshipRepository = new FriendshipRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $notificationRepository = new NotificationRepository(Database::getConnection());
        $this->chatRepository = new ChatRepository(Database::getConnection());

        $sessionRepository->deleteAll();
        $friendshipRepository->deleteAll();
        $notificationRepository->deleteAll();
        $this->chatRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    private function registerHelper(string $username, string $name, string $password) {
        $user = new User();
        $user->setUsername($username);
        $user->setName($name);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));

        $this->userRepository->save($user);
    }

    public function testSaveAndFindChatsByFUAndTUSuccess() {
        $this->registerHelper('jokO', 'joko13', '123');
        $this->registerHelper('Budi', 'budii13', '123');

        $chat = new Chat();
        $chat->setFromUser('joko');
        $chat->setMessage('yoolooo');
        $chat->setTargetUser('budi');
        $this->chatRepository->save($chat);

        $result = $this->chatRepository->findChatsByFUAndTU($chat);

        $this->assertSame(1, sizeof($result));
        $this->assertSame('yoolooo', $result[0]->getMessage());
    }

    public function testSaveFailed() {
        $this->expectException(Exception::class);
        $this->registerHelper('Budi', 'budii13', '123');

        $chat = new Chat();
        $chat->setFromUser('joko');
        $chat->setMessage('yoolooo');
        $chat->setTargetUser('budi');
        $this->chatRepository->save($chat);
    }

    public function testFindChatsByFUAndTUFailed() {
        $this->registerHelper('jokO', 'joko13', '123');
        $this->registerHelper('Budi', 'budii13', '123');

        $chat = new Chat();
        $chat->setFromUser('joko');
        $chat->setMessage('yoolooo');
        $chat->setTargetUser('budi');
        $this->chatRepository->save($chat);

        $chat->setTargetUser('aih');
        $result = $this->chatRepository->findChatsByFUAndTU($chat);

        $this->assertSame(0, sizeof($result));
    }
}