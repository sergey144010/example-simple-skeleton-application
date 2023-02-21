<?php

namespace App;

use App\Models\ModelInterface;
use Aura\SqlQuery\QueryFactory;

class EntityManager
{
    private const START_SEQUENSION = 1;

    private array $storage = [];
    private bool $transaction = false;

    public function __construct(private Connection $connection, private QueryFactory $queryFactory)
    {
    }

    public function persist(ModelInterface $model, PersistOptions $options = null): void
    {
        $key = get_class($model);

        if (array_key_exists($key, $this->storage)) {
            $this->storage[$key]['id']++;
            $this->storage[$key]['stack'][] = (new ModelReflector())
                ->fill(
                    $model,
                    [
                        'id' => $this->storage[$key]['id']
                    ]
                );

            return;
        }

        if (isset($options)) {
            $this->storage[$key]['table'] = $options->table;
        } else {
            $match = explode("\\", $key);
            $this->storage[$key]['table'] = strtolower($match[count($match) - 1]);
        }

        $this->storage[$key]['id'] = self::START_SEQUENSION;
        $this->storage[$key]['stack'][] = (new ModelReflector())
            ->fill(
                $model,
                [
                    'id' => $this->storage[$key]['id']
                ]
            );
    }

    public function flush(): void
    {
        $insert = $this->queryFactory->newInsert();

        $this->beginTransaction();

        try {
            foreach ($this->storage as $key => $pack) {
                foreach ($pack['stack'] as $model) {
                    $refObject = new \ReflectionObject($model);
                    $injectColsVals = [];
                    $props = $refObject->getProperties(\ReflectionProperty::IS_PRIVATE);
                    foreach ($props as $prop) {
                        $propName = $prop->getName();
                        if ($propName === 'id') {
                            continue;
                        }
                        try {
                            $injectColsVals[$propName] = $model->$propName();
                        } catch (\Throwable) {
                            $injectColsVals[$propName] = null;
                        }
                    }
                    $insert->into($pack['table'])->cols($injectColsVals);

                    $statement = $this->connection->connection()->prepare($insert->getStatement());
                    $statement->execute($insert->getBindValues());
                }
                $this->storage[$key]['id'] = $this->connection->connection()->lastInsertId();
                $this->storage[$key]['stack'] = [];
            }
        } catch (\Throwable) {
            $this->rollback();
        }

        $this->commit();
    }

    private function beginTransaction(): void
    {
        if ($this->connection->connection()->inTransaction()) {
            return;
        }

        $this->transaction = true;
        $this->connection->connection()->beginTransaction();
    }

    private function commit(): void
    {
        if (! $this->transaction) {
            return;
        }

        $this->connection->connection()->commit();
    }

    private function rollback(): void
    {
        if (! $this->transaction) {
            return;
        }

        $this->connection->connection()->rollBack();
    }
}
