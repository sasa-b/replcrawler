<?php

/** @noinspection ForgottenDebugOutputInspection */

declare(strict_types=1);

namespace SasaB\REPLCrawler\Util;

use Traversable;

/**
 * @template T
 */
abstract class Collection implements \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * @param array<int,T> $items
     */
    final public function __construct(protected array $items)
    {
    }

    public function print(): self
    {
        print_r($this->items);
        return $this;
    }

    /**
     * @param int $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * @param int $offset
     * @return T|null
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * @param int|null $offset
     * @param T $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * @param int $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    public function count(): int
    {
        return \count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function isNotEmpty(): bool
    {
        return $this->count() > 0;
    }

    /**
     * @return Traversable<int,T>
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @return array<int,T>
     */
    public function all(): array
    {
        return $this->items;
    }

    public function filter(\Closure $filter): static
    {
        return new static(array_filter($this->items, $filter));
    }
}
