<?php

namespace App\Tests\Unit;

use App\Connection;
use App\EntityManager;
use App\Models\ModelInterface;
use App\Models\User;
use Aura\SqlQuery\QueryFactory;
use PHPUnit\Framework\TestCase;

class EntityManagerTest extends TestCase
{
    public function testStorageHasModelName(): void
    {
        $connection = $this->createMock(Connection::class);
        $queryFactory = $this->createMock(QueryFactory::class);

        $model = new User('Name', 'LastName');
        $em = new EntityManager($connection, $queryFactory);
        $em->persist($model);

        $className = get_class($model);

        $refObject = new \ReflectionObject($em);
        $storage = $refObject->getProperty('storage')->getValue($em);

        self::assertArrayHasKey($className, $storage);
    }

    public function testStorageOneModelState(): void
    {
        $connection = $this->createMock(Connection::class);
        $queryFactory = $this->createMock(QueryFactory::class);

        $model = new User('Name', 'LastName');
        $em = new EntityManager($connection, $queryFactory);
        $em->persist($model);

        $className = get_class($model);

        $refObject = new \ReflectionObject($em);
        $storage = $refObject->getProperty('storage')->getValue($em);

        self::assertEquals($storage[$className]['id'], 1);
        self::assertCount(1, $storage[$className]['stack']);
        self::assertInstanceOf(ModelInterface::class, $storage[$className]['stack'][0]);
        self::assertEquals($storage[$className]['table'], 'user');
        self::assertEquals('Name', $storage[$className]['stack'][0]->name());
    }

    public function testStorageTwoModelState(): void
    {
        $connection = $this->createMock(Connection::class);
        $queryFactory = $this->createMock(QueryFactory::class);

        $model1 = new User('Name1', 'LastName1');
        $model2 = new User('Name2', 'LastName2');
        $model3 = new User('Name3', 'LastName3');
        $em = new EntityManager($connection, $queryFactory);
        $em->persist($model1);
        $em->persist($model2);
        $em->persist($model3);

        $className = get_class($model1);

        $refObject = new \ReflectionObject($em);
        $storage = $refObject->getProperty('storage')->getValue($em);

        self::assertEquals($storage[$className]['id'], 3);
        self::assertCount(3, $storage[$className]['stack']);
        self::assertEquals(3, $storage[$className]['stack'][2]->id());
    }
}