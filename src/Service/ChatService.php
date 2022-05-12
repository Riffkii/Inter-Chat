<?php

namespace Web\InterChat\Service;
use Web\InterChat\Repository\ChatRepository;
use Web\InterChat\Model\Request\SendMessageRequest;
use Web\InterChat\Model\Response\SendMessageResponse;
use Web\InterChat\Exception\ValidationException;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Database\Chat;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Model\Request\LoadMessageRequest;
use Web\InterChat\Model\Response\LoadMessageResponse;

class ChatService {

    public function __construct(private ChatRepository $chatRepository,
                                private SessionRepository $sessionRepository) {}

    public function sendMessage(SendMessageRequest $request): SendMessageResponse {
        try {
            Database::startTransaction();
            $this->sendMessageValidation($request);
            $session = $this->sessionRepository->findById($_COOKIE['X-LOG-SESSION']);

            $chat = new Chat();
            $chat->setFromUser($session->getUserUsername());
            $chat->setMessage($request->getMessage());
            $chat->setTargetUser($request->getTargetUser());
            $this->chatRepository->save($chat);

            $response = new SendMessageResponse();
            $response->setChat($chat);
            Database:: commit();
            return $response;
        } catch (ValidationException $e) {
            Database::rollback();
            throw $e;
        }
    }

    public function sendMessageValidation(SendMessageRequest $request) {
        if(trim($request->getMessage()) == '') {
            throw new ValidationException('Message must not blank');
        }
    }

    public function loadMessage(LoadMessageRequest $request): LoadMessageResponse {
        try {
            Database::startTransaction();
            $session = $this->sessionRepository->findById($_COOKIE['X-LOG-SESSION']);

            $chat = new Chat();
            $chat->setFromUser($session->getUserUsername());
            $chat->setTargetUser($request->getTargetUser());
            $data = $this->chatRepository->findChatsByFUAndTU($chat);

            $response = new LoadMessageResponse();
            $response->setChats($data);
            Database::commit();
            return $response;
        } catch (ValidationException $e) {
            Database::rollback();
            throw $e;
        }
    }
}