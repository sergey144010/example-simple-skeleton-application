<?php

namespace App\Tests\Unit;

use App\Models\Property\PropertyCreator;
use App\Models\Property\Sanitizer\Alfabet;
use App\Models\Property\Sanitizer\Trim;
use App\Models\Validators\UserValidator;
use PHPUnit\Framework\TestCase;

class PropertyCreatorTest extends TestCase
{
    public function testNameIsEmpty(): void
    {
        $this->expectExceptionMessage('Name is empty');

        PropertyCreator::create('name', '   ', new UserValidator(), new Trim());
    }

    public function testNameIsShort(): void
    {
        $this->expectExceptionMessage('Name is too short');

        PropertyCreator::create('name', 'On', new UserValidator(), new Trim());
    }

    public function testNameIsLong(): void
    {
        $this->expectExceptionMessage('Name is too long');

        PropertyCreator::create(
            'name',
            '012345678901234567890123456789012345678901234567890123456789',
            new UserValidator(),
            new Trim(),
        );
    }

    public function testNameBetweenShortAndLong(): void
    {
        $name = PropertyCreator::create(
            'name',
            'NormalName',
            new UserValidator(),
            new Trim(),
        );

        self::assertEquals('NormalName', $name);
    }

    public function testTwoSanitizerNameIsEmpty(): void
    {
        $this->expectExceptionMessage('Name is empty');

        PropertyCreator::create('name', ' 123 ', new UserValidator(), new Trim(new Alfabet()));
    }

    public function testOnlyOneWorld(): void
    {
        $name = PropertyCreator::create('name', ' First Second ', new UserValidator(), new Trim(new Alfabet()));

        self::assertEquals('First', $name);
    }
}