<?php

namespace Rrim\PhpUserManagement\Validation;

use Rrim\PhpUserManagement\Domain\User;
use Rrim\PhpUserManagement\Exception\ValidationException;
use Rrim\PhpUserManagement\Model\UpdatePasswordRequest;
use Rrim\PhpUserManagement\Model\UpdateProfileRequest;
use Rrim\PhpUserManagement\Model\UserLoginRequest;
use Rrim\PhpUserManagement\Model\UserRegisterRequest;


class Validation
{
    private User $user;

    public function __construct(public string $message)
    {

    }

    private function blank($request): void
    {
        if ($request == null || trim($request) == "") {
            throw new ValidationException("$this->message can't blank");
        }

    }

    public function registerValidation(UserRegisterRequest $request): void
    {
        $this->blank($request->id);
        $this->blank($request->name);
        $this->blank($request->password);

    }

    public function loginValidation(UserLoginRequest $request): void
    {
        $this->blank($request->id);
        $this->blank($request->password);
    }

    public function updateProfileValidation(UpdateProfileRequest $request): void
    {
        $this->blank($request->id);
        $this->blank($request->name);
    }

    public function updatePasswordValidation(UpdatePasswordRequest $request):void
    {
        $this->blank($request->id);
        $this->blank($request->oldPassword);
        $this->blank($request->newPassword);

    }


}