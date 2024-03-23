<?php

namespace Rrim\PhpUserManagement\Service;

use Exception;
use Rrim\PhpUserManagement\Config\Database;
use Rrim\PhpUserManagement\Domain\User;
use Rrim\PhpUserManagement\Exception\ValidationException;
use Rrim\PhpUserManagement\Log\Log;
use Rrim\PhpUserManagement\Model\UpdatePasswordRequest;
use Rrim\PhpUserManagement\Model\UpdatePasswordResponse;
use Rrim\PhpUserManagement\Model\UpdateProfileRequest;
use Rrim\PhpUserManagement\Model\UpdateProfileResponse;
use Rrim\PhpUserManagement\Model\UserLoginRequest;
use Rrim\PhpUserManagement\Model\UserLoginResponse;
use Rrim\PhpUserManagement\Model\UserRegisterRequest;
use Rrim\PhpUserManagement\Model\UserRegisterResponse;
use Rrim\PhpUserManagement\Repository\UserRepository;
use Rrim\PhpUserManagement\Validation\Validation;

class UserService
{
    private UserRepository $userRepository;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserRegisterRequest $request) : UserRegisterResponse
    {
        $validator = new Validation("id, name, and password");
        $validator->registerValidation($request);

        try {
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->id);
            if ($user != null){
                throw new ValidationException("User id already exist");

            }else{
                $user = new User();
                $user->id = $request->id;
                $user->name = $request->name;
                $user->password = password_hash($request->password, PASSWORD_BCRYPT);

                $this->userRepository->save($user);
                $response = new UserRegisterResponse();
                $response->user = $user;

                Database::commitTransaction();
                return $response;
            }
        } catch (Exception $exception){
            Database::rollbackTransaction();
            throw $exception;

        }


    }


    /**
     * @throws ValidationException
     */
    public function login(UserLoginRequest $request) : UserLoginResponse
    {

        $validation = new Validation("id and password");
        $validation->loginValidation($request);

        $user = $this->userRepository->findById($request->id);
        if ($user==null){
            throw new ValidationException("id and password is wrong");
        }

        if (password_verify($request->password,$user->password)){
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        }else{
            throw new ValidationException("id and password is wrong");
        }

    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function updateProfile(UpdateProfileRequest $request):UpdateProfileResponse
    {
        $validation = new Validation("Name");
        $validation->updateProfileValidation($request);

        try {
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->id);
            if ($user==null){
                throw new ValidationException("User is not Found");
            }

            $user->name = $request->name;

            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UpdateProfileResponse();
            $response->user = $user;
            return $response;


        }catch (Exception $exception){
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    public function updatePassword(UpdatePasswordRequest $request) : UpdatePasswordResponse
    {
        $validation = new Validation("Password");
        $validation->updatePasswordValidation($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);

            if ($user==null){
                throw new ValidationException("User is not found");
            }

            if (!password_verify($request->oldPassword,$user->password)){
                throw new ValidationException("Wrong password");
            }

            $user->password = password_hash($request->newPassword,PASSWORD_BCRYPT);
            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UpdatePasswordResponse();
            $response->user = $user;
            return $response;

        }catch (ValidationException $exception){
            Database::rollbackTransaction();
            throw $exception;
        }


    }

}