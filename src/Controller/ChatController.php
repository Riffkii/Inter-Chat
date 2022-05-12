<?php

namespace Web\InterChat\Controller;
use Web\InterChat\Service\ChatService;
use Web\InterChat\Util\View;
use Web\InterChat\Repository\ChatRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Exception\ValidationException;
use Web\InterChat\Model\Request\SendMessageRequest;
use Exception;
use Web\InterChat\Model\Request\LoadMessageRequest;

class ChatController {

    private UserRepository $userRepository;
    private ChatService $chatService;

    public function __construct() {
        $this->userRepository = new UserRepository(Database::getConnection('app'));
        $sessionRepository = new SessionRepository(Database::getConnection('app'));
        $chatRepository = new ChatRepository(Database::getConnection('app'));
        $this->chatService = new ChatService($chatRepository, $sessionRepository);
    }
    
    public function chat() {
        $data = $this->userRepository->findByUsername($_GET['target']);
        View::render('Chat', [
            'title' => 'Chat',
            'target' => $data->getName(),
            'tUsername' => $data->getUsername()
        ]);
    }

    public function postChat() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            $request = new SendMessageRequest();
            $request->setMessage($data['message']);
            $request->setTargetUser($data['target']);
            $this->chatService->sendMessage($request);
        } catch (ValidationException $e) {
            $data = $this->userRepository->findByUsername($_GET['target'])->getName();
            View::render('Chat', [
            'title' => 'Chat',
            'target' => $data,
            'error' => $e->getMessage()
            ]);
        }
    }

    public function message() {
        try {
            $request = new LoadMessageRequest();
            $request->setTargetUser($_GET['target']);
            $response = $this->chatService->loadMessage($request);
            echo json_encode($response->getChats());
        } catch (Exception $e) {

        }
    }
}