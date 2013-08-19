<?php

namespace pjdietz\ComplexSort;

use InvalidArgumentException;

class ComplexSort
{
    /**
     * Given an ordered array of comparison functions, return one function that
     * starts with the first and uses the subsequent functions in order in the
     * event of equal items.
     *
     * @param $sortFnArr
     * @param int $index
     * @return callable
     * @throws InvalidArgumentException
     */
    public static function makeComplexSortFunction($sortFnArr, $index = 0)
    {
        if (isset($sortFnArr[$index])) {
            $fn1 = $sortFnArr[$index];
        } else {
            throw new InvalidArgumentException('First argument must be an array conatining at least one callable');
        }

        $fn2 = null;
        if (isset($sortFnArr[$index + 1])) {
            $fn2 = self::makeComplexSortFunction($sortFnArr, $index + 1);
        }

        return self::makeChainedSortFunction($fn1, $fn2);
    }

    /**
     * Given two comparison functions, return a comparison function that uses
     * the first unless it evaluates as equal, then uses the second.
     *
     * @param $fn1
     * @param $fn2
     * @return callable
     */
    private static function makeChainedSortFunction($fn1, $fn2)
    {
        if (!is_callable($fn2)) {
            return $fn1;
        }

        return function ($a, $b) use ($fn1, $fn2) {
            $comp = $fn1($a, $b);
            if ($comp !== 0) {
                return $comp;
            }
            return $fn2($a, $b);
        };
    }
}
