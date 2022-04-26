<?php

/** @noinspection ForgottenDebugOutputInspection */

declare(strict_types=1);

namespace SasaB\REPLCrawler\Util;

/**
 * @template TCValue
 */
abstract class Collection implements \Iterator, \Countable, \ArrayAccess
{
    /**
     * @param array<TCValue> $items
     */
    public function __construct(protected array $items)
    {
    }

    public function print(): self
    {
        print_r($this->items);
        return $this;
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * @param int $offset
     * @return TCValue|null
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * @param int|null $offset
     * @param TCValue $value
     * @return void
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
     * @return void
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
     * @return TCValue
     */
    public function current(): mixed
    {
        return current($this->items);
    }

    public function next(): void
    {
        next($this->items);
    }

    public function key(): int|string|null
    {
        return key($this->items);
    }

    public function valid(): bool
    {
        return key($this->items) !== null;
    }

    public function rewind(): void
    {
        reset($this->items);
    }

    /**
     * @return array<TCValue>
     */
    public function all(): array
    {
        return $this->items;
    }
}
