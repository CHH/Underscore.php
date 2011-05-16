<?php

namespace Underscore\Test;

use Underscore as _;

class ChainingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testdox Chaining: map/flatten/reduce
     */
    function testChainingMapFlattenReduce()
    {
        $lyrics = array(
            "I'm a lumberjack and I'm okay",
            "I sleep all night and I work all day",
            "He's a lumberjack and he's okay",
            "He sleeps all night and he works all day"
        );

        $counts = _\chain($lyrics)
            ->map(function($line) { return str_split($line); })
            ->flatten()
            ->reduce(function($hash, $l) {
                $hash[$l] = empty($hash[$l]) ? 0 : $hash[$l];
                $hash[$l]++;
                return $hash;
            }, array())->value();

        $this->assertEquals(16, $counts["a"]);
        $this->assertEquals(10, $counts["e"]);
    }

    function testChainingSelectRejectSortBy()
    {
        $numbers = range(1, 10);
        $numbers = _\chain($numbers)->select(function($n) {
            return $n % 2 == 0;
        })->reject(function($n) {
            return $n % 4 == 0;
        })->sortBy(function($n) {
            return -$n;
        })->value();

        $this->assertEquals("10, 6, 2", join(', ', $numbers));
    }

    function testReverseConcatUnshiftPopMap()
    {
        $numbers = range(1, 5);
        $numbers = _\chain($numbers)
            ->reverse()
            ->concat(array(5, 5, 5))
            ->unshift(17)
            ->pop()
            ->map(function($n) { return $n * 2; })
            ->value();

        $this->assertEquals("34, 10, 8, 6, 4, 2, 10, 10", join(', ', $numbers));
    }
}
