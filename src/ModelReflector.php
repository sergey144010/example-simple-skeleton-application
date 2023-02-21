<?php

namespace App;

use App\Models\ModelInterface;
use App\Models\Property\PropertyCreator;
use App\Models\Validators\NothingValidator;
use App\Models\Validators\ValidatorInterface;

class ModelReflector
{
    public function fill(
        ModelInterface $model,
        array $properties = [],
        ValidatorInterface $validator = new NothingValidator()
    ): ModelInterface {
        $refObject = new \ReflectionObject($model);
        foreach ($properties as $property => $value) {
            try {
                $preparedValue = PropertyCreator::create(
                    $property,
                    $value,
                    $validator
                );

                if ($refObject->hasProperty($property)) {
                    $refProperty = $refObject->getProperty($property);
                    $refProperty->setValue($model, $preparedValue);
                }
            } catch (\Throwable $exception) {
                $this->eventDataException($exception);
            }
        }

        return $model;
    }

    private function eventDataException(\Throwable $exception): void
    {
        # throw new ValidatorException('Data from db broken' . $exception->getMessage());
    }
}
