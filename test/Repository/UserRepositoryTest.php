<?php

namespace Rrim\PhpUserManagement\Repository;

use PHPUnit\Framework\TestCase;
use Rrim\PhpUserManagement\Config\Database;
use Rrim\PhpUserManagement\Domain\User;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp():void
    {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $sessionRepository->deleteAll();
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();

    }

    public function testSaveSuccess()
    {
        $user = new User();
        $user->id = "Farhan";
        $user->name = "Farhan";
        $user->password = "Rahasia";

        $this->userRepository->save($user);
        $result = $this->userRepository->findById($user->id);

        self::assertEquals($user->id,$result->id);

    }

    public function testFindByIDNotFound()
    {
        $result = $this->userRepository->findById("notFound");

        self::assertNull($result);

    }

    public function testUpdateSuccess()
    {
        $user = new User();
        $user->id = "Farhan";
        $user->name = "Farhan";
        $user->password = "Rahasia";

        $this->userRepository->save($user);

        $user->name = "Anto";
        $userUpdate = $this->userRepository->update($user);

        self::assertEquals($user->id,$userUpdate->id);
        self::assertEquals($user->name,$userUpdate->name);
        self::assertEquals($user->password,$userUpdate->password);


    }


}
