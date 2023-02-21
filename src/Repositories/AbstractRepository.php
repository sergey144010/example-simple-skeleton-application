<?php

namespace App\Repositories;

use App\Collection;
use App\Connection;
use App\ModelReflector;
use App\Models\User;
use App\Models\Validators\UserValidator;
use Aura\SqlQuery\QueryFactory;

class AbstractRepository
{
    public function __construct(protected Connection $connection, protected QueryFactory $queryFactory)
    {
    }

    public function get(string $statement, array $bindValues): array
    {
        $sth = $this->connection->connection()->prepare($statement);
        $sth->execute($bindValues);

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function resultToUserCollection(array $result): Collection
    {
        $collection = new Collection();
        foreach ($result as $user) {
            $collection->add(
                (new ModelReflector())->fill(
                    new User($user['name'], $user['lastName']),
                    [
                        'id' => $user['id'],
                        'from' => $user['from'],
                        'age' => $user['age'],
                        'key' => $user['key'],
                    ],
                    new UserValidator(),
                )
            );
        }

        return $collection;
    }
}
