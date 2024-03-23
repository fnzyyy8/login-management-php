<?php

namespace Rrim\PhpUserManagement\Service;

use PHPUnit\Framework\TestCase;
use Rrim\PhpUserManagement\Config\Database;
use Rrim\PhpUserManagement\Domain\User;
use Rrim\PhpUserManagement\Exception\ValidationException;
use Rrim\PhpUserManagement\Model\UpdatePasswordRequest;
use Rrim\PhpUserManagement\Model\UpdateProfileRequest;
use Rrim\PhpUserManagement\Model\UserLoginRequest;
use Rrim\PhpUserManagement\Model\UserRegisterRequest;
use Rrim\PhpUserManagement\Repository\SessionRepository;
use Rrim\PhpUserManagement\Repository\UserRepository;
use function PHPUnit\Framework\assertTrue;

class UserServiceTest extends TestCase
{

    private UserService $userService;
    private UserRepository $userRepository;

    protected function setUp():void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $sessionRepository = new SessionRepository($connection);

        $this->userService = new UserService($this->userRepository);

        $sessionRepository->deleteAll();
        $this->userRepository->deleteAll();


    }

    public function testServiceSuccess()
    {
        $request = new UserRegisterRequest();
        $request->id = "Farhan";
        $request->name = "Farhan";
        $request->password = "rahasia";

        $response = $this->userService->register($request);

        self::assertEquals($request->id,$response->user->id );
        self::assertEquals($request->name,$response->user->name);
        self::assertNotEquals($request->password,$response->user->password);

    }

    public function testFailed()
    {
        $this->expectException(ValidationException::class);
        $request = new UserRegisterRequest();
        $request->id = "";
        $request->name = "";
        $request->password = "";
        $this->userService->register($request);

    }

    public function testDuplicate()
    {
        $this->expectException(ValidationException::class);

        $user = new User();
        $user->id = "Farhan";
        $user->name = "Farhan";
        $user->password = "rahasia";
        $this->userRepository->save($user);

        $request = new UserRegisterRequest();
        $request->id = "Farhan";
        $request->name = "Farhan";
        $request->password = "rahasia";

        $this->userService->register($request);
    }

    public function testLoginNotFound()
    {
        $this->expectException(ValidationException::class);
        $request = new UserLoginRequest();
        $request->id = "Farhan";
        $request->password = "Farhan";
        $this->userService->login($request);

    }

    public function testLoginWrongPassword()
    {

        $this->expectException(ValidationException::class);
        $user = new User();
        $user->id = "Farhan";
        $user->password = password_hash("Farhan",PASSWORD_BCRYPT);

        $request = new UserLoginRequest();
        $request->id = "Farhan";
        $request->password = "Anto";

        $this->userService->login($request);

    }

    public function testLoginSuccess()
    {
        $user = new User();
        $user->id = "Farhan";
        $user->name ="Farhan";
        $user->password = password_hash("Farhan",PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $request = new UserLoginRequest();
        $request->id = "Farhan";
        $request->password = "Farhan";

       $response =  $this->userService->login($request);

        self::assertEquals($user->id,$response->user->id);
        self::assertTrue(password_verify($request->password,$response->user->password));

    }

    /**
     * @throws ValidationException
     */
    public function testUpdateSuccess()
    {
        $user = new User();
        $user->id = "Farhan";
        $user->name ="Farhan";
        $user->password = password_hash("Farhan",PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $request = new UpdateProfileRequest();
        $request->id = "Farhan";
        $request->name = "Anto";

        $response = $this->userService->updateProfile($request);

        self::assertEquals($response->user->name,"Anto");
    }

    public function testUpdateFailed()
    {
        $this->expectException(ValidationException::class);

        $request = new UpdateProfileRequest();
        $request->id = "Anto";
        $request->name = "Anto";

        $response = $this->userService->updateProfile($request);

        self::assertEquals($response->user->name,"Anto");

    }

    public function testUpdatePasswordSuccess()
    {
        $user = new User();
        $user->id = "Farhan";
        $user->name ="Farhan";
        $user->password = password_hash("Farhan",PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $request = new UpdatePasswordRequest();
        $request->id = "Farhan";
        $request->oldPassword = "Farhan";
        $request->newPassword = "Anto";

        $this->userService->updatePassword($request);

        $result = $this->userRepository->findById($request->id);

        self::assertTrue(password_verify($request->newPassword,$result->password));
    }

    public function testUpdatePasswordFailed()
    {
        self::expectException(ValidationException::class);
        $user = new User();
        $user->id = "Farhan";
        $user->name ="Farhan";
        $user->password = password_hash("Farhan",PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $request = new UpdatePasswordRequest();
        $request->id = "Farhan";
        $request->oldPassword = "Anto";
        $request->newPassword = "Farhan";

        $this->userService->updatePassword($request);
        $result = $this->userRepository->findById($request->id);

        self::assertTrue(password_verify($request->newPassword,$result->password));

    }


}

