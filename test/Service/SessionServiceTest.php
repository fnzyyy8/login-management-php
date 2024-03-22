<?php

namespace Rrim\PhpUserManagement\Service;

use PHPUnit\Framework\TestCase;
use Rrim\PhpUserManagement\Config\Database;
use Rrim\PhpUserManagement\Domain\Session;
use Rrim\PhpUserManagement\Domain\User;
use Rrim\PhpUserManagement\Repository\SessionRepository;
use Rrim\PhpUserManagement\Repository\UserRepository;

function setCookie(string $name, string $value): void
{
echo "$name: $value";
}

class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;

    protected function setUp() : void
    {

    $this->sessionRepository = new SessionRepository(Database::getConnection());
    $userRepository = new UserRepository(Database::getConnection());
    $this->sessionService = new SessionService($this->sessionRepository, $userRepository);

    $this->sessionRepository->deleteAll();
    $userRepository->deleteAll();

    $user = new User();
    $user->id = "Farhan";
    $user->name = "Farhan";
    $user->password = "rahasia";
    $userRepository->save($user);


    }

    public function testCreate()
    {
        $session = $this->sessionService->create("Farhan");
        $this->expectOutputRegex("[X-RRIM-SESSION: $session->id]");

        $result = $this->sessionRepository->findById($session->id);
        self::assertEquals($session->id,$result->id);


    }

    public function testDestroy()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "Farhan";

        $this->sessionRepository->save($session);
        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->sessionService->destroy();
        $this->expectOutputRegex("[X-RRIM-SESSION: ]");


    }

    public function testCurrent()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "Farhan";
        $this->sessionRepository->save($session);
        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();

        self::assertEquals($session->user_id,$user->id);


    }





}
