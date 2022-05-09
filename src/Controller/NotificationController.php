<?php

namespace Web\InterChat\Controller;
use Web\InterChat\Service\NotificationService;
use Web\InterChat\Repository\NotificationRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Util\View;
use Exception;
use Error;
use Web\InterChat\Model\Request\ShowAllNotificationsRequest;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Service\SessionService;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Model\Request\AddNotificationRequest;

class NotificationController {

    private SessionService $sessionService;
    private NotificationService $notificationService;

    public function __construct() {
        $userRepository = new UserRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
        $notificationRepository = new NotificationRepository(Database::getConnection('app'));
        $this->notificationService = new NotificationService($notificationRepository, $this->sessionService);
    }

    public function notification() {
        View::render('Notification', [
            'title' => 'Notification',
            'h1' => 'Notification'
        ]);
    }

    public function getNotification() {
        try {
            $data = $this->notificationService->showAllNotifications();
            header("Content-Type: application/json");
            header("Accept: application/json");
            echo json_encode($data);
        } catch (Exception | Error $e) {

        }
    }

    public function postNotification() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $currentUser = $this->sessionService->current();

            $request = new AddNotificationRequest();
            $request->setUsername($data['target']);
            $request->setMessageFrom($currentUser->getUsername());
            $request->setMessage($currentUser->getName() . $data['message']);

            $this->notificationService->addNotification($request);
        } catch (Exception | Error $e) {

        }
    }
}