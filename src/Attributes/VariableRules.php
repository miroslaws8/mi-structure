<?php

namespace Structure\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class VariableRules
{
    public function __construct(public int $length, public mixed $equals = null)
    {}
}