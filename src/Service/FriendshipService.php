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
use Web\InterChat\Model\Request\FindFriendRequest;
use Web\InterChat\Model\Response\FindFriendResponse;

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

    public function findFriend(FindFriendRequest $request): FindFriendResponse {
        try {
            Database::startTransaction();
            $session = $this->sessionRepository->findById($_COOKIE['X-LOG-SESSION']);

            $friendship = new Friendship();
            $friendship->setUser1Username($session->getUserUsername());
            $friendship->setUser2Username($request->getToUser());
            $friends = $this->friendshipRepository->findFriendByName($friendship);

            $response = new FindFriendResponse();
            $response->setFriends($friends);
            Database::commit();
            return $response;
        } catch (Exception | Error $e) {
            Database::rollback();
            throw $e;
        }
    }

    public function findNotFriend(FindNotFriendRequest $request): FindNotFriendResponse {
        try {
            Database::startTransaction();
            $session = $this->sessionRepository->findById($_COOKIE['X-LOG-SESSION']);

            $friendship = new Friendship();
            $friendship->setUser1Username($session->getUserUsername());
            $friendship->setUser2Username($request->getToUser());
            $notFriends = $this->friendshipRepository->findNotFriendByName($friendship);

            $response = new FindNotFriendResponse();
            $response->setNotFriends($notFriends);
            Database::commit();
            return $response;
        } catch (Exception | Error $e) {
            Database::rollback();
            throw $e;
        }
    }

    public function checkFriend(string $target) {
        try {
            Database::startTransaction();
            $session = $this->sessionRepository->findById($_COOKIE['X-LOG-SESSION']);
            $friends = $this->friendshipRepository->findFriendsByUsername($session->getUserUsername());

            foreach($friends as $friend) {
                if($friend->getUsername() == $target) {
                    return json_encode(['success' => 'true']);
                }
            }
            Database::commit();
        } catch (Exception | Error $e) {
            Database::rollback();
            throw $e;
        }
    }

    public function showFriends(): array {
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

    public function findOnlineFriends(): array|null {
        try {
            Database::startTransaction();
            $onlineUsers = $this->sessionRepository->findAll();
            $session = $this->sessionRepository->findById($_COOKIE['X-LOG-SESSION']);
            $friends = $this->friendshipRepository->findFriendsByUsername($session->getUserUsername());

            $onlineFriends = [];
            foreach($friends as $friend) {
                foreach($onlineUsers as $onlineUser) {
                    if($friend->getUsername() == $onlineUser->getUserUsername()) {
                        $onlineFriends[] = $friend;
                    }
                }
            }
            Database::commit();
            return $onlineFriends;
        } catch (Exception | Error $e) {
            Database::rollback();
        }
    }

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