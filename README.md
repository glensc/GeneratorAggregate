# GeneratorAggregate

`GeneratorAggregate` is a solution for PHP 5.5+ to handle missing `yield from`:

```php
<?php

use glen\GeneratorAggregate\GeneratorAggregate;

function subgenerator()
{
    yield new ArrayIterator(['c', 'd']);
    yield 'e';
}

function generator()
{
    yield new ArrayIterator([1, 2, 3]);
    yield new ArrayIterator(['a', 'b']);
    yield subgenerator();
}

$generator = new GeneratorAggregate(generator());

print_r($generator->toArray());

```

```
Array
(
    [0] => 1
    [1] => 2
    [2] => 3
    [3] => a
    [4] => b
    [5] => c
    [6] => d
    [7] => e
)
```