<?php

namespace Web\InterChat\Service;
use Web\InterChat\Repository\FriendshipRepository;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Request\AddFriendRequest;
use Web\InterChat\Repository\SessionRepository;
use Web\InterChat\Model\Database\Friendship;
use Web\InterChat\Model\Response\AddFriendResponse;
use Exception;
use Error;
use Web\InterChat\Model\Request\UnfriendRequest;
use Web\InterChat\Model\Response\UnfriendResponse;
use Web\InterChat\Model\Request\FindNotFriendRequest;
use Web\InterChat\Model\Response\FindNotFriendResponse;

class FriendshipService {

    public function __construct(private FriendshipRepository $friendshipRepository,
                                private SessionRepository $sessionRepository) {}

    public function addFriend(AddFriendRequest $request): AddFriendResponse {
        try {
            Database::startTransaction();
            $session = $this->sessionRepository->findById($_COOKIE['X-LOG-SESSION']);
            
            $friendship = new Friendship();
            $friendship->setUser1Username($session->getUserUsername());
            $friendship->setUser2Username($request->getToUser());
            $this->friendshipRepository->save($friendship);

            $response = new AddFriendResponse();
            $response->setFriendship($friendship);
            Database::commit();
            return $response;
        } catch (Exception $e) {
            Database::rollback();
            throw $e;
        }
    }

    public function findFriend() {

    }

    public function findNotFriend(FindNotFriendRequest $request): FindNotFriendResponse {
        try {
            Database::startTransaction();
            $session = $this->sessionRepository->findById($_COOKIE['X-LOG-SESSION']);

            $friendship = new Friendship();
            $friendship->setUser1Username($session->getUserUsername());
            $friendship->setUser2Username($request->getToUser());
            $notFriends = $this->friendshipRepository->findNotFriendByUsername($friendship);

            $response = new FindNotFriendResponse();
            $response->setNotFriends($notFriends);
            Database::commit();
            return $response;
        } catch (Exception | Error $e) {
            Database::rollback();
            throw $e;
        }
    }

    public function showFriends() {
        try {
            Database::startTransaction();
            $session = $this->sessionRepository->findById($_COOKIE['X-LOG-SESSION']);
            $friends = $this->friendshipRepository->findFriendsByUsername($session->getUserUsername());
            Database::commit();
            return $friends;
        } catch (Exception | Error $e) {
            Database::rollback();
            throw $e;
        }
    }

    // public function showNotFriends(): array {
    //     try {
    //         Database::startTransaction();
    //         $session = $this->sessionRepository->findById($_COOKIE['X-LOG-SESSION']);
    //         $notFriends = $this->friendshipRepository->findNotFriendsByUsername($session->getUserUsername());
    //         Database::commit();
    //         return $notFriends;
    //     } catch (Exception | Error $e) {
    //         Database::rollback();
    //         throw $e;
    //     }
    // }

    public function unfriend(UnfriendRequest $request): UnfriendResponse {
        try {
            Database::startTransaction();
            $session = $this->sessionRepository->findById($_COOKIE['X-LOG-SESSION']);

            $friendship = new Friendship();
            $friendship->setUser1Username($session->getUserUsername());
            $friendship->setUser2Username($request->getToUser());
            $this->friendshipRepository->deleteFriendsByUsername($friendship);

            $response = new UnfriendResponse();
            $response->setFriendship($friendship);
            Database::commit();
            return $response;
        } catch (Exception | Error $e) {
            Database::rollback();
            throw $e;
        }
    }
}