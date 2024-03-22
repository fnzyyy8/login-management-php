<?php

namespace Rrim\PhpUserManagement\Middleware;

use Rrim\PhpUserManagement\App\View;
use Rrim\PhpUserManagement\Config\Database;
use Rrim\PhpUserManagement\Repository\SessionRepository;
use Rrim\PhpUserManagement\Repository\UserRepository;
use Rrim\PhpUserManagement\Service\SessionService;

class sessionHasTrue implements Middleware
{
    private SessionService $sessionService;

    public function __construct()
    {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository,$userRepository);
    }


    public function before(): void
    {
        $user = $this->sessionService->current();

        if ($user != null){
            View::redirect('/');
        }
    }
}