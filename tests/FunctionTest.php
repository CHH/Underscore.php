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

    function testOnce()
    {
        $num = 0;
        $increment = _\once(function() use (&$num) { 
            $num++; 
        });

        $increment();
        $increment();

        $this->assertEquals(1, $num);
    }

    function testWrap()
    {
        $greet = function($name) {
            return "hi: $name";
        };

        $backwards = _\wrap($greet, function($func, $name) {
            return $func($name) . ' ' . strrev($name);
        });

        $this->assertEquals(
            "hi: moe eom", 
            $backwards("moe"), 
            "Wrapped the Salutation function"
        );
    }

    function testCompose()
    {
        $greet = function($name) {
            return "hi: $name";
        };
        $exclaim = function($sentence) {
            return $sentence . '!';
        };
        $composed = _\compose($exclaim, $greet);

        $this->assertEquals("hi: moe!", $composed("moe"));

        $composed = _\compose($greet, $exclaim);
        $this->assertEquals("hi: moe!", $composed("moe"));
    }

    function testAfter()
    {
        $testAfter = function($afterAmount, $timesCalled) {
            $afterCalled = 0;
            $after = _\after($afterAmount, function() use (&$afterCalled) {
                $afterCalled++;
            });
            while ($timesCalled--) {
                $after();
            }
            return $afterCalled;
        };

        $this->assertEquals(1, $testAfter(5, 5));
        $this->assertEquals(0, $testAfter(5, 4));
    }
}
