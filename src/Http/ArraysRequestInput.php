<?php
declare(strict_types=1);

namespace Forme\Framework\Http;

trait ArraysRequestInput
{
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->input()[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return isset($this->input()[$offset]) ? $this->input()[$offset] : null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->input()[] = $value;
        } else {
            $this->input()[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->input()[$offset]);
    }
}
