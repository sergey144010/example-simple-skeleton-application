<?php

namespace App;

class Collection implements \IteratorAggregate, \ArrayAccess
{
    private array $collection = [];

    public function add(mixed $item): void
    {
        $this->collection[] = $item;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->collection);
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->collection);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->collection[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->collection[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->collection[$offset]);
    }

    public function get(): array
    {
        return $this->collection;
    }
}
