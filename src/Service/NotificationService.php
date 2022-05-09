<?php

namespace Web\InterChat\Service;
use Web\InterChat\Repository\NotificationRepository;
use Web\InterChat\Exception\ValidationException;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Request\AddNotificationRequest;
use Web\InterChat\Model\Database\Notification;
use Web\InterChat\Model\Response\AddNotificationResponse;
use Exception;
use Error;

class NotificationService {

    public function __construct(private NotificationRepository $notificationRepository,
                                private SessionService $sessionService) {}

    public function addNotification(AddNotificationRequest $request): AddNotificationResponse {
        try {
            Database::startTransaction();
            
            $notification = new Notification();
            $notification->setUsername($request->getUsername());
            $notification->setMessageFrom($request->getMessageFrom());
            $notification->setMessage($request->getMessage());
            $lastInsertId = $this->notificationRepository->save($notification);
            $notification->setID($lastInsertId);

            $response = new AddNotificationResponse();
            $response->setNotification($notification);
            Database::commit();
            return $response;
        } catch (Exception | Error $e) {
            Database::rollback();
            throw $e;
        }
    }

    public function showAllNotifications(): array {
        try {
            Database::startTransaction();
            $user = $this->sessionService->current();
            $data = $this->notificationRepository->getMessagesByUsername($user->getUsername());            
            Database::commit();
            return $data;
        } catch (Exception | Error $e) {
            Database::rollback();
            throw $e;
        }
    }

    public function showUsernameByMF(): array {
        try {
            Database::startTransaction();
            $user = $this->sessionService->current();
            $data = $this->notificationRepository->getMessageByMF($user->getUsername());  
            Database::commit();
            return $data;
        } catch (Exception | Error $e) {
            Database::rollback();
            throw $e;
        }
    }

    public function deleteNotification(int $id) {
        try {
            Database::startTransaction();
            $this->notificationRepository->deleteById($id);
            Database::commit();
        } catch (Exception | Error $e) {
            Database::rollback();
            throw $e;
        }
    }
}