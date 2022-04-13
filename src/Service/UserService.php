<?php

namespace Web\InterChat\Service;
use Web\InterChat\Repository\UserRepository;
use Web\InterChat\Model\Request\UserRegisterRequest;
use Web\InterChat\Exception\ValidationException;
use Web\InterChat\Model\Response\UserRegisterResponse;
use Web\InterChat\Model\Database\User;
use Web\InterChat\Util\Database;
use Web\InterChat\Model\Request\UserLoginRequest;
use Web\InterChat\Model\Response\UserLoginResponse;

class UserService {

    public function __construct(private UserRepository $userRepository) {}

    public function register(UserRegisterRequest $request): UserRegisterResponse {
        try {
            Database::startTransaction();
            $this->registerValidation($request);

            $result = $this->userRepository->findByUsername($request->getUsername());
            if($result != null) {
                throw new ValidationException("User already exist");
            } else {
                $user = new User();
                $user->setUsername($request->getUsername());
                $user->setName($request->getName());
                $user->setPassword(password_hash($request->getPassword(), PASSWORD_BCRYPT));
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
           trim($userRegisterRequest->getUsername()) == '' || trim($userRegisterRequest->getName()) == '' || trim($userRegisterRequest->getPassword()) == '') {
                throw new ValidationException("Username, Name, or Password can not blank");
           }
    }

    public function login(UserLoginRequest $request): ?UserLoginResponse {
        try {
            Database::startTransaction();
            $this->loginValidation($request);

            $result = $this->userRepository->findByUsername($request->getUsername());
            if($result != null) {
                if(password_verify($request->getPassword(), $result->getPassword())) {
                    $response = new UserLoginResponse();
                    $response->setUser($result);
                    Database::commit();
                    return $response;
                }
            }
            throw new ValidationException("Username or Password is wrong");
        } catch(ValidationException $e) {
            Database::rollback();
            throw $e;
        }
    }

    public function loginValidation(UserLoginRequest $request) {
        if($request->getUsername() == null || $request->getPassword() == null ||
           trim($request->getUsername()) == '' || trim($request->getPassword()) == '') {
                throw new ValidationException("Username or Password can not blank");
           }
    }
}