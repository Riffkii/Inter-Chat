<?php

namespace Web\InterChat\Controller;
use Web\InterChat\Service\FriendshipService;
use Web\InterChat\Repository\FriendshipRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Util\View;
use Error;
use Exception;
use Web\InterChat\Model\Request\FindNotFriendRequest;
use Web\InterChat\Model\Request\AddFriendRequest;
use Web\InterChat\Service\NotificationService;
use Web\InterChat\Service\SessionService;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Repository\NotificationRepository;
use Web\InterChat\Model\Request\FindFriendRequest;
use phpDocumentor\Reflection\Types\This;
use Web\InterChat\Model\Request\UnfriendRequest;

class FriendshipController {

    private NotificationService $notificationService;
    private FriendshipService $friendshipService;

    public function __construct() {
        $userRepository = new UserRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection('app'));
        $sessionService = new SessionService($sessionRepository, $userRepository);
        $notificationRepository = new NotificationRepository(Database::getConnection());
        $this->notificationService = new NotificationService($notificationRepository, $sessionService);
        $friendshipRepository = new FriendshipRepository(Database::getConnection('app'));
        $this->friendshipService = new FriendshipService($friendshipRepository, $sessionRepository);
    }

    public function findFriend() {
        View::render('FindFriend', [
            'title' => 'Inter Chat'
        ]);
    }

    public function postFindFriend() {
        try {
            $request = new FindNotFriendRequest();
            $request->setToUser($_POST['input']);

            $data = $this->friendshipService->findNotFriend($request);

            $notifications = $this->notificationService->showUsernameByMF();
            View::render('FindFriend', [
                'title' => 'Inter Chat',
                'data' => $data->getNotFriends(),
                'check' => $notifications
            ]);
        } catch (Exception | Error $e) {

        }
    }

    public function postAddFriend() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            $request = new AddFriendRequest();
            $request->setToUser($data['target']);

            $this->friendshipService->addFriend($request);

            $this->notificationService->deleteNotification($data['id']);
        } catch (Exception | Error $e) {

        }
    }

    public function checkFriend() {
        try {
            $friend = $this->friendshipService->checkFriend($_GET['target']);
            header("Content-Type: application/json");
            header("Accept: application/json");
            echo $friend;
        } catch (Exception | Error $e) {

        }
    }

    public function showFriends() {
        try {
            $friends = $this->friendshipService->showFriends();
            header("Content-Type: application/json");
            header("Accept: application/json");
            echo json_encode($friends);
        } catch (Exception | Error $e) {

        }
    }

    public function postUnfriend() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            $request = new UnfriendRequest();
            $request->setToUser($data['target']);
            $this->friendshipService->unfriend($request);
        } catch (Exception | Error $e) {

        }
    }

    public function showOnlineFriends() {
        try {
            $onlineFriends = $this->friendshipService->findOnlineFriends();
            header("Content-Type: application/json");
            header("Accept: application/json");
            echo json_encode($onlineFriends);
        } catch (Exception | Error $e) {

        }
    }

    public function searchFriend() {
        try {
            $request = new FindFriendRequest();
            $request->setToUser($_GET['friend']);

            $friends = $this->friendshipService->findFriend($request);
            header("Content-Type: application/json");
            header("Accept: application/json");
            echo json_encode($friends->getFriends());
        } catch (Exception | Error $e) {

        }
    }

    public function friends() {
        View::render('Friends', [
            'title' => 'Friends'
        ]);
    }
}