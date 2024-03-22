<?php

namespace Rrim\PhpUserManagement\Controller;

use Rrim\PhpUserManagement\App\View;
use Rrim\PhpUserManagement\Config\Database;
use Rrim\PhpUserManagement\Repository\SessionRepository;
use Rrim\PhpUserManagement\Repository\UserRepository;
use Rrim\PhpUserManagement\Service\SessionService;

class HomeController
{
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }


    public function index()
    {
        $user = $this->sessionService->current();

        if ($user==null){
            View::render('Home/index',[
                'title'=>"Php User Management"
            ]);
        }else{
        View::render('Home/dashboard',[
            'title'=>'dashboard',
            'user'=>[
                'name'=>$user->name
            ]
        ]);
    }
        
  }
       

}