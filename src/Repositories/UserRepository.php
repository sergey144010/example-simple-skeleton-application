<?php

namespace App\Repositories;

use App\Collection;
use Aura\SqlQuery\Common\SelectInterface;

class UserRepository extends AbstractRepository
{
    private SelectInterface $select;
    private string $table = 'user';

    public function find(array $criteria = []): Collection
    {
        $this->select = $this->queryFactory->newSelect();

        $this->select->from($this->table);
        $this->select->cols(['*']);
        foreach ($criteria as $param => $value) {
            $this->select->where($param . ' = :' . $param);
            $this->select->bindValue($param, $value);
        }

        return $this->resultToUserCollection(
            $this->get($this->select->getStatement(), $this->select->getBindValues())
        );
    }

    public function findByName(string $name): Collection
    {
        return $this->find(['name' => $name]);
    }

    public function findByNames(array $list): Collection
    {
        $this->select = $this->queryFactory->newSelect();

        $this->select->from($this->table);
        $this->select->cols(['*']);

        /**
         * library bag, not work
         * @see https://github.com/auraphp/Aura.SqlQuery/issues/193
         */
        /** @phpstan-ignore-next-line */
        $this->select->where('name IN (:name)', ['name' => $list]);

        return $this->resultToUserCollection(
            $this->get($this->select->getStatement(), $this->select->getBindValues())
        );
    }

    public function findOlderAge(int $age): Collection
    {
        $this->select = $this->queryFactory->newSelect();

        $this->select->from($this->table);
        $this->select->cols(['*']);
        $this->select->where('age > :age');
        $this->select->bindValue('age', $age);

        return $this->resultToUserCollection(
            $this->get($this->select->getStatement(), $this->select->getBindValues())
        );
    }
}
