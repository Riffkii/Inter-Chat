<?php

namespace Web\InterChat\Service;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Model\Request\UserRegisterRequest;
use Web\InterChat\Exception\ValidationException;
use Web\InterChat\Model\Response\UserRegisterResponse;
use Web\InterChat\Model\Database\User;
use Web\InterChat\Util\Database;

class UserService {

    public function __construct(private UserRepository $userRepository) {}

    public function register(UserRegisterRequest $userRegisterRequest): UserRegisterResponse {
        try {
            Database::startTransaction();
            $this->registerValidation($userRegisterRequest);

            $result = $this->userRepository->findByUsername($userRegisterRequest->getUsername());
            if($result != null) {
                throw new ValidationException("User already exist");
            } else {
                $user = new User();
                $user->setUsername($userRegisterRequest->getUsername());
                $user->setName($userRegisterRequest->getName());
                $user->setPassword(password_hash($userRegisterRequest->getPassword(), PASSWORD_BCRYPT));
                $this->userRepository->save($user);

                $response = new UserRegisterResponse();
                $response->setUser($user);
                Database::commit();
                return $response;
            }
        } catch(ValidationException $e) {
            Database::rollback();
            throw $e;
        }
    }

    public function registerValidation(UserRegisterRequest $userRegisterRequest) {
        if($userRegisterRequest->getUsername() == null || $userRegisterRequest->getName() == null || $userRegisterRequest->getPassword() == null ||
           trim($userRegisterRequest->getUsername()) == '' || trim($userRegisterRequest->getName()) == '' || trim($userRegisterRequest->getPassword() == '')) {
                throw new ValidationException("Username, Name, or Password can not blank");
           }
    }
}