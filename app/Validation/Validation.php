<?php

namespace Rrim\PhpUserManagement\Validation;

use Rrim\PhpUserManagement\Exception\ValidationException;
use Rrim\PhpUserManagement\Model\UpdatePasswordRequest;
use Rrim\PhpUserManagement\Model\UpdateProfileRequest;
use Rrim\PhpUserManagement\Model\UserLoginRequest;
use Rrim\PhpUserManagement\Model\UserRegisterRequest;

class Validation
{

    private string $message;


    public function __construct(string $message)
    {
        $this->message = $message;
    }


    /**
     * @throws ValidationException
     */
    public function registerValidation(UserRegisterRequest $request): void
    {
        $this->isBlank($request->id);
        $this->isBlank($request->name);
        $this->isBlank($request->password);


    }

    /**
     * @throws ValidationException
     */
    public function loginValidation(UserLoginRequest $request): void
    {
        $this->isBlank($request->id);
        $this->isBlank($request->password);

    }

    /**
     * @throws ValidationException
     */
    public function updateValidation(UpdateProfileRequest $request):void
    {
        $this->isBlank($request->id);
        $this->isBlank($request->name);

    }

    public function passwordValidation(UpdatePasswordRequest $request):void
    {
        $this->isBlank($request->id);
        $this->isBlank($request->oldPassword);
        $this->isBlank($request->newPassword);

    }

    /**
     * @throws ValidationException
     */
    private function isBlank($value): void
    {
        if ($value == null || trim($value) == ""){
            throw new ValidationException("$this->message can't blank");
        }

    }


}