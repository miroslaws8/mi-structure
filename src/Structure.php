<?php

namespace Structure;

use JetBrains\PhpStorm\Pure;
use ReflectionException;
use Structure\Attributes\MapFrom;
use Structure\Attributes\VariableRules;
use Structure\Reflections\StructureProperty;

abstract class Structure implements Interfaces\Structure
{
    #[MapFrom(name: 'user_id')]
    #[VariableRules(length: 10, equals: 5)]
    public int $userId;

    /**
     * @throws ReflectionException
     * @throws Exceptions\StructureValidateException
     */
    public function __construct(public array $properties)
    {
        (new StructureProperty($this))->validate();
    }

    #[Pure]
    public function __call(string $name, array $arguments): mixed
    {
        foreach ($this->decompose() as $property => $value) {
            if (mb_strpos(strtolower($name), strtolower($property)) !== false) {
                return $this->{$property};
            }
        }

        return null;
    }

    public function decompose(): array
    {
        return get_class_vars(self::class);
    }
}