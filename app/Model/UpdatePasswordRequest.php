<?php

namespace Rrim\PhpUserManagement\Model;

class UpdatePasswordRequest
{
    public ?string $id = null;
    public ?string $oldPassword = null;
    public ?string $newPassword = null;

}