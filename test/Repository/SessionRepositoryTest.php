<?php

namespace Rrim\PhpUserManagement\Repository;

use PHPUnit\Framework\TestCase;
use Rrim\PhpUserManagement\Config\Database;
use Rrim\PhpUserManagement\Domain\Session;
use Rrim\PhpUserManagement\Domain\User;
use function PHPUnit\Framework\assertNull;

class SessionRepositoryTest extends TestCase
{

    private SessionRepository $sessionRepository;

    protected function setUp():void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $userRepository->deleteAll();

        $user = new User();
        $user->id = "Farhan";
        $user->name = "Farhan";
        $user->password = "rahasia";
        $userRepository->save($user);
    }

    public function testSaveSuccess()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "Farhan";
        $result = $this->sessionRepository->save($session);

        self::assertEquals($session->id,$result->id);
        self::assertEquals($session->user_id,$result->user_id);

    }

    public function testDeleteById()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "Farhan";
        $result = $this->sessionRepository->save($session);

        self::assertEquals($session->id,$result->id);
        self::assertEquals($session->user_id,$result->user_id);

        $result = $this->sessionRepository->deleteById($session->id);

       self::assertNull($result);

    }

    public function testFindByIdNotFound()
    {
        $result = $this->sessionRepository->findById("kosong");

        assertNull($result);

    }


}
