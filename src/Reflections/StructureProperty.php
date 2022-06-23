<?php

namespace Structure\Reflections;

use ReflectionException;
use Structure\Attributes\MapFrom;
use Structure\Attributes\VariableRules;
use Structure\Exceptions\StructureValidateException;
use Structure\Interfaces\Structure;
use Structure\Validator\VariableValidator;

class StructureProperty
{
    /**
     * @throws ReflectionException
     * @throws StructureValidateException
     */
    public function __construct(private Structure $structure)
    {
        foreach ($structure->decompose() as $property => $value) {
            $reflection = new \ReflectionProperty($structure::class, $property);
            $attributes = $reflection->getAttributes();

            $mapFromAttribute = array_filter($attributes, fn ($attribute) => $attribute->getName() === MapFrom::class);

            if (!$mapFromAttribute) {
                continue;
            }

            foreach ((array) $mapFromAttribute[0]->newInstance() as $attribute) {
                $this->structure->{$property} = $this->structure->properties[$attribute];
            }
        }
    }

    /**
     * @throws ReflectionException|StructureValidateException
     */
    public function validate(): bool
    {
        $validator = new VariableValidator();

        foreach ($this->structure->decompose() as $property => $value) {
            $reflection = new \ReflectionProperty($this->structure::class, $property);

            $attributes = array_filter(
                $reflection->getAttributes(),
                fn ($attribute) => $attribute->getName() === VariableRules::class
            );

            if (!$attributes) {
                continue;
            }

            $attribute = array_pop($attributes);

            foreach ((array) $attribute->newInstance() as $rule => $ruleValue) {
                if ($ruleValue && !$validator->{$rule}($this->structure->{$property}, $ruleValue)) {
                    throw new StructureValidateException("$rule of $property is invalid");
                }
            }
        }

        return true;
    }
}