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

class FriendshipController {

    private FriendshipService $friendshipService;

    public function __construct() {
        $sessionRepository = new SessionRepository(Database::getConnection('app'));
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
            View::render('FindFriend', [
                'title' => 'Inter Chat',
                'data' => $data->getNotFriends()
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
        } catch (Exception | Error $e) {

        }
    }

    public function postShowFriend() {

    }

    public function unfriend() {
        
    }

    public function postUnfriend() {
        
    }
}