<?php

namespace App\Tests\Unit;

use App\Connection;
use App\EntityManager;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserManager;
use Aura\SqlQuery\QueryFactory;
use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
    private Connection $connection;
    private QueryFactory $queryFactory;

    public function setUp(): void
    {
        $appConf = new \App\Config(__DIR__ . '/../../config/app.php');
        #fwrite(STDERR, print_r($appConf->get(), TRUE));
        $this->queryFactory = new QueryFactory('mysql');
        $this->connection = (new Connection($appConf));
        $this->connection->connection()->beginTransaction();
    }

    public function testCreateDefaultUser()
    {
        $userManager = (new UserManager(
            (new EntityManager($this->connection, $this->queryFactory)),
            new UserRepository($this->connection, $this->queryFactory)
        ));

        $userManager->createUser('OneName', 'OneLastName');

        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])->from('user');
        $sth = $this->connection->connection()->prepare($select->getStatement());
        $sth->execute($select->getBindValues());

        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        self::assertCount(1, $result);
        self::assertEquals('OneName', $result[0]['name']);
        self::assertEquals('OneLastName', $result[0]['lastName']);
        self::assertNull($result[0]['from']);
        self::assertNull($result[0]['age']);
        self::assertNotNull($result[0]['key']);

        $userRepository = new UserRepository($this->connection, $this->queryFactory);
        $collection = $userRepository->find();
        self::assertCount(1, $collection);

        /** @var User $user */
        $user = $collection[0];

        self::assertIsInt($user->id());
        self::assertEquals('OneName', $user->name());
        self::assertEquals('OneLastName', $user->lastName());
        self::assertNotNull($user->key());
    }

    public function testCreateDefaultUserFromException()
    {
        $this->expectExceptionMessage('from property not setup');

        $userManager = (new UserManager(
            (new EntityManager(clone $this->connection, $this->queryFactory)),
            new UserRepository($this->connection, $this->queryFactory)
        ));

        $userManager->createUser('OneName', 'OneLastName');

        $userRepository = new UserRepository($this->connection, $this->queryFactory);
        $collection = $userRepository->find();
        self::assertCount(1, $collection);
        /** @var User $user */
        $user = $collection[0];

        $user->from();
    }

    public function testCreateDefaultUserAgeException()
    {
        $this->expectExceptionMessage('age property not setup');

        $userManager = (new UserManager(
            (new EntityManager(clone $this->connection, $this->queryFactory)),
            new UserRepository($this->connection, $this->queryFactory)
        ));

        $userManager->createUser('OneName', 'OneLastName');

        $userRepository = new UserRepository($this->connection, $this->queryFactory);
        $collection = $userRepository->find();
        self::assertCount(1, $collection);
        /** @var User $user */
        $user = $collection[0];

        $user->age();
    }

    public function tearDown(): void
    {
        $this->connection->connection()->rollBack();
    }
}