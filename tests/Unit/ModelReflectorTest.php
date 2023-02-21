<?php

namespace App\Tests\Unit;

use App\ModelReflector;
use App\Models\ModelInterface;
use App\Models\User;
use App\Models\Validators\UserValidator;
use PHPUnit\Framework\TestCase;

class ModelReflectorTest extends TestCase
{
    public function testFillModel()
    {
        /** @var User $model */
        $model = (new ModelReflector())->fill(
            new User('First', 'Last'),
            [
                'age' => 21,
                'from' => 'Same place',
                'key' => '12eq'
            ]
        );

        self::assertInstanceOf(ModelInterface::class, $model);

        self::assertEquals(21, $model->age());
        self::assertEquals('Same place', $model->from());
        self::assertEquals('12eq', $model->key());
    }

    public function testAgeYoung()
    {
        $this->expectExceptionMessage('age property not setup');

        /** @var User $model */
        $model = (new ModelReflector())->fill(
            new User('First', 'Last'),
            [
                'age' => 10,
            ],
            new UserValidator()
        );

        $model->age();
    }

    public function testFrom()
    {
        $this->expectExceptionMessage('from property not setup');

        /** @var User $model */
        $model = (new ModelReflector())->fill(
            new User('First', 'Last'),
            [
                'from' => 'on',
            ],
            new UserValidator()
        );

        $model->from();
    }

    public function testKey()
    {
        $this->expectExceptionMessage('key property not setup');

        /** @var User $model */
        $model = (new ModelReflector())->fill(
            new User('First', 'Last'),
            [
                'key' => '123',
            ],
            new UserValidator()
        );

        $model->key();
    }

    public function testFromSuccess()
    {
        /** @var User $model */
        $model = (new ModelReflector())->fill(
            new User('First', 'Last'),
            [
                'from' => 'Same state',
            ],
            new UserValidator()
        );

        self::assertEquals('Same state', $model->from());
    }
}