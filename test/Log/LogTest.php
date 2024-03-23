<?php

namespace Rrim\PhpUserManagement\Log;

use PHPUnit\Framework\TestCase;
use Rrim\PhpUserManagement\Config\Database;
use Rrim\PhpUserManagement\Domain\User;
use Rrim\PhpUserManagement\Repository\SessionRepository;
use Rrim\PhpUserManagement\Repository\UserRepository;
use Rrim\PhpUserManagement\Service\UserService;

class LogTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;




    public function testSuccessLog()
    {
        $user = new User();
        $user->id = "Farhan";
        $user->name = "Farhan";

        $logger = Log::logLogin('info', $user);

        
        self::assertNull($logger);


    }


}
