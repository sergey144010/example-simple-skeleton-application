<?php

namespace App\Tests\Unit;

use App\Models\Exceptions\UserModelException;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private function createUser(): User
    {
        return new User('First', 'Last');
    }

    public function testUserExceptionFrom()
    {
        $this->expectException(UserModelException::class);

        $user = $this->createUser();
        $user->from();
    }

    public function testUserExceptionAge()
    {
        $this->expectException(UserModelException::class);

        $user = $this->createUser();
        $user->age();
    }

    public function testUserExceptionKey()
    {
        $this->expectException(UserModelException::class);

        $user = $this->createUser();
        $user->key();
    }
}