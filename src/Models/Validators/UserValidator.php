<?php

namespace App\Models\Validators;

use App\Models\Exceptions\UserValidatorException;

class UserValidator extends AbstractValidator
{
    public function id(int $id): void
    {
        if (empty($id)) {
            throw new UserValidatorException('Id is empty');
        }
    }

    public function name(string $name): void
    {
        if (empty($name)) {
            throw new UserValidatorException('Name is empty');
        }

        if (strlen($name) < 3) {
            throw new UserValidatorException('Name is too short');
        }

        if (strlen($name) > 50) {
            throw new UserValidatorException('Name is too long');
        }
    }

    public function lastName(string $lastName): void
    {
        if (empty($lastName)) {
            throw new UserValidatorException('LastName is empty');
        }

        if (strlen($lastName) < 3) {
            throw new UserValidatorException('LastName is too short');
        }

        if (strlen($lastName) > 50) {
            throw new UserValidatorException('LastName is too long');
        }
    }

    public function age(?int $age = null): void
    {
        if (! isset($age)) {
            return;
        }

        if ($age < 18) {
            throw new UserValidatorException('Age is too young');
        }

        if ($age > 60) {
            throw new UserValidatorException('No longer makes sense');
        }
    }

    public function from(?string $from = null): void
    {
        if (! isset($from)) {
            return;
        }

        if (empty($from)) {
            throw new UserValidatorException('From is empty');
        }

        if (strlen($from) < 3) {
            throw new UserValidatorException('From is too short');
        }

        if (strlen($from) > 50) {
            throw new UserValidatorException('From is too long');
        }
    }

    public function key(string $key): void
    {
        if (empty($key)) {
            throw new UserValidatorException('Key is empty');
        }

        if (strlen($key) !== 64) {
            throw new UserValidatorException('Key broken');
        }
    }
}
