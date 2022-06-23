<?php

namespace Structure\Validator;

class VariableValidator
{
    public function length(mixed $value, int $needed): bool
    {
        return $needed >= strlen($value);
    }

    public function equals(mixed $value, mixed $needed): bool
    {
        return $needed === $value;
    }
}