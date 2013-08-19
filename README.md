ComplexSort
===========

ComplexSort allows you to chain together multiple comparison functions for us in PHP's `usort` function. For example, if you have a an array of object, each with the fields `firstName`, `nickname`, `age`, and `favoriteColor`, and you've created comparison functions for sort by each of these fields individually, you can use ComplexSort to build a new comparison function that combines these in whichever order you like.

Here's an example. Say way have this array:

```php
// Here's some data.
$data = array(
    array(
        'firstName' => 'George',
        'nickname' => 'Geroge Sr.',
        'age' => 62,
        'favoriteColor' => 'orange'
    ),
    array(
        'firstName' => 'George',
        'nickname' => 'GOB',
        'age' => 42,
        'favoriteColor' => 'blue'
    ),
    array(
        'firstName' => 'George',
        'nickname' => 'George Michael',
        'age' => 16,
        'favoriteColor' => 'blue'
    ),
    array(
        'firstName' => 'Buster',
        'nickname' => 'Buster',
        'age' => 32,
        'favoriteColor' => 'violet'
    )
);
```

And suppose we have these functions for sorting individually:

```php
// Some comparison functions. These aren't particular *good* ones, as they do
// nothing as far as checking if the array keys exist, but they'll work for
// this demo.

function compareFirstName($a, $b) {
    if ($a['firstName'] == $b['firstName']) {
        return 0;
    }
    return $a['firstName'] > $b['firstName'] ? 1 : -1;
}

function compareAge($a, $b) {
    if ($a['age'] == $b['age']) {
        return 0;
    }
    return $a['age'] > $b['age'] ? 1 : -1;
}

function compareColor($a, $b) {
    if ($a['favoriteColor'] == $b['favoriteColor']) {
        return 0;
    }
    return $a['favoriteColor'] > $b['favoriteColor'] ? 1 : -1;
}
```

We can make new comparison functions that order these individual functions in whichever order we need.

```php
// Arrange some sort functions in a partiular order in an array.
// Pass that array to ComplexSort::makeComplexSortFunction() to combine them.
// Use this complex function with usort() to sort the data.
$sortArrays = array('compareFirstName', 'compareAge', 'compareColor');
$complexFn = ComplexSort::makeComplexSortFunction($sortArrays);
usort($data, $complexFn);

print "By firstName, age, favoriteColor: \n";
foreach ($data as $bluth) {
    print $bluth['nickname'] . "\n";
}

// By firstName, age, favoriteColor:
// Buster
// George Michael
// GOB
// Geroge Sr.

$sortArrays = array('compareColor', 'compareAge');
$complexFn = ComplexSort::makeComplexSortFunction($sortArrays);
usort($data, $complexFn);

print "By favoriteColor, age: \n";
foreach ($data as $bluth) {
    print $bluth['nickname'] . "\n";
}

// By favoriteColor, age:
// George Michael
// GOB
// Geroge Sr.
// Buster

```

For a copy-and-paste version of the same functionality, see my [original gist](https://gist.github.com/pjdietz/5292681).

Happy sorting!
