<?php

namespace App\Models;

use App\Models\Exceptions\UserModelException;
use App\Models\Property\PropertyCreator;
use App\Models\Property\Sanitizer\Alfabet;
use App\Models\Property\Sanitizer\Trim;
use App\Models\Validators\UserValidator;

class User implements ModelInterface
{
    /** @phpstan-ignore-next-line */
    private int $id;
    private string $name = '';
    private string $lastName = '';
    private string $from;
    private int $age;
    private string $key;

    public function __construct(string $name, string $lastName)
    {
        $this->name = PropertyCreator::create('name', $name, new UserValidator(), new Trim(new Alfabet()));
        $this->lastName = PropertyCreator::create('lastName', $lastName, new UserValidator(), new Trim(new Alfabet()));
    }

    public function id(): int
    {
        if (! isset($this->id)) {
            throw new UserModelException('id property not setup');
        }

        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function from(): string
    {
        if (! isset($this->from)) {
            throw new UserModelException('from property not setup');
        }

        return $this->from;
    }

    public function setFrom(string $from): void
    {
        $this->from = PropertyCreator::create('from', $from, new UserValidator(), new Trim(new Alfabet()));
    }

    public function age(): int
    {
        if (! isset($this->age)) {
            throw new UserModelException('age property not setup');
        }

        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = PropertyCreator::create('age', $age, new UserValidator(), new Trim(new Alfabet()));
    }

    public function key(): string
    {
        if (! isset($this->key)) {
            throw new UserModelException('key property not setup');
        }

        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = PropertyCreator::create('key', $key, new UserValidator(), new Trim());
    }
}
