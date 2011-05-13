<?php

namespace Underscore\Test;

use Underscore as _;

class FunctionTest extends \PHPUnit_Framework_TestCase
{
    function testMemoize()
    {
        function fib($n) {
            return $n < 2 ? $n : fib($n - 1) + fib($n - 2);
        }

        $fastFib = _\memoize("\\Underscore\\Test\\fib");

        $this->assertEquals(55, fib(10), "a memoized version of fibonacci produces identical results");
        $this->assertEquals(55, $fastFib(10), "a memoized version of fibonacci produces identical results");
    }
}
