<?php

namespace Structure;

use JetBrains\PhpStorm\Pure;
use ReflectionException;
use Structure\Reflections\StructureProperty;

abstract class Structure implements Interfaces\Structure
{
    /**
     * @throws ReflectionException
     * @throws Exceptions\StructureValidateException
     */
    public function __construct(public array $properties = [])
    {
        (new StructureProperty($this))->validate();
    }

    #[Pure]
    public function __call(string $name, array $arguments): mixed
    {
        foreach ($this->getStructureVariables() as $property => $value) {
            if (mb_strpos(strtolower($name), strtolower($property)) !== false) {
                return $this->{$property};
            }
        }

        return null;
    }

    public function decompose(): array
    {
        return get_object_vars($this);
    }

    public function getStructureVariables(): array
    {
        return array_keys(get_class_vars(self::class));
    }
}