<?php

namespace Rrim\PhpUserManagement\Controller;

use Rrim\PhpUserManagement\App\View;
use Rrim\PhpUserManagement\Config\Database;
use Rrim\PhpUserManagement\Exception\ValidationException;
use Rrim\PhpUserManagement\Model\UpdatePasswordRequest;
use Rrim\PhpUserManagement\Model\UpdateProfileRequest;
use Rrim\PhpUserManagement\Model\UserLoginRequest;
use Rrim\PhpUserManagement\Model\UserRegisterRequest;
use Rrim\PhpUserManagement\Repository\SessionRepository;
use Rrim\PhpUserManagement\Repository\UserRepository;
use Rrim\PhpUserManagement\Service\SessionService;
use Rrim\PhpUserManagement\Service\UserService;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;


    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }


    public function register()
    {
        View::render('User/register',[
            'title' => "User Register"
        ]);

    }

    public function postRegister()
    {
        $request = new UserRegisterRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
        $request->password = $_POST['password'];

        try {
            $this->userService->register($request);
            View::redirect("/users/login");

        }catch (ValidationException $exception){
            View::render('User/register',[
                'title' => "User Register",
                'error' => $exception->getMessage()

            ]);
        }
        
    }

    public function login()
    {
        View::render('User/login',[
            'title'=>"User login"
        ]);
        
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->id = $_POST['id'];
        $request->password = $_POST['password'];

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->id);
            View::redirect('/');

        }catch (ValidationException $exception){
            View::render('User/login',[
                'title'=>"User login",
                'error'=>$exception->getMessage()
            ]);
        }
        
    }

    public function logout()
    {
        $this->sessionService->destroy();
        View::redirect('/');
    }

    public function profile()
    {
        $user = $this->sessionService->current();
        View::render("User/profile",[
            "title"=>"Update User Profile",
            "user"=>[
                "id"=>$user->id,
                "name"=>$user->name,
            ]
        ]);
        
    }

    public function postProfile()
    {
        $user = $this->sessionService->current();
        $request = new UpdateProfileRequest();
        $request->id = $user->id;
        $request->name = $_POST['name'];

        try {
            $this->userService->updateProfile($request);
            View::redirect("/");
        }catch (ValidationException $exception){
            View::render("User/profile",[
                "title"=>"Update User Profile",
                "error"=>$exception->getMessage(),
                "user"=>[
                    "id"=>$user->id,
                    "name"=>$user->name]
            ]);
        }
    }

    public function updatePassword()
    {
        $user = $this->sessionService->current();
        View::render("User/password",[
            "Title" => "Update Password",
            "user"=>[
                "id"=>$user->id,
            ]
        ]);
    }

    public function postUpdatePassword()
    {
        $user = $this->sessionService->current();
        $request = new UpdatePasswordRequest();
        $request->id = $user->id;
        $request->oldPassword = $_POST['oldPassword'];
        $request->newPassword = $_POST['newPassword'];

        try {
            $this->userService->updatePassword($request);
            View::redirect("/");

        }catch (ValidationException $exception){
            View::render("User/password",[
                "Title" => "Update Password",
                "user"=>[
                    "id"=>$user->id,
                ],
                "error"=>$exception->getMessage()
            ]);
        }

    }
}