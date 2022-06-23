# PHP-STRUCTURE

This package is tries to represent a struct from other languages.

### Getting started

```php
class MyStructure extends \Structure\Structure
{
    public int $userId = 1;
}

$userId = (new MyStructure())->userId;

// $userId = 1;
```

OR

```php

class MyStructure extends \Structure\Structure
{
    #[MapFrom(name: 'user_id')]
    public int $userId;
}

$userData = [
    'user_id' => 10
];

$structure = (new MyStructure($data));

$userId = $structure->userId;
// $userId = 10;

$userId = $structure->getUserId();
// $userId = 10;
```

### Rules

```php
class MyStructure extends \Structure\Structure
{
    #[VariableRules(length: 5, equals: 'Iron Man')]
    public string $name = 'Miro';
}

$userId = (new MyStructure())->name;
// There will be an exception "StructureValidateException"
```