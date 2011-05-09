<?php

namespace Underscore;

require "underscore.php";

use Underscore as _;

class Test extends \PHPUnit_Framework_TestCase
{
    function testCanDoThingsManyTimes()
    {
        $count = 0;
    
        _\perform(3)->times(function() use (&$count) {
            $count++;
        });

        $this->assertEquals(3, $count);
    }

    function testFrom()
    {
        $this->assertTrue(_\from(array(1, 2, 3)) instanceof _\Chain);
    }

    function testSelectPreservesKeys()
    {
        $result = _\from(array("foo", "bar", "baz"))->select(function($value) {
            return $value[0] == 'b';
        });

        $this->assertEquals(array(1 => "bar", 2 => "baz"), $result->value());
    }
    
    function testCamelize()
    {
        $string1 = "foo-bar-baz";
        $string2 = "foo_bar_baz";

        $expect  = "FooBarBaz";

        $this->assertEquals($expect, _\camelize($string1));
        $this->assertEquals($expect, _\camelize($string2));
    }

    function testIdentity()
    {
        $k = _\identity();

        $this->assertTrue(is_callable($k));
        $this->assertEquals("foo", $k("foo"));
    }

    function testWordsToArray()
    {
        $string = "apple banana pear plum";
        $words  = _\words($string);

        $this->assertEquals(4, sizeof($words));
    }

    function testWrapFunction()
    {
        $arg = "foo";

        $wrapper = function($original, $arg) {
            return $original($arg);
        };

        $fn = function($arg) {
            return $arg;
        };

        $newFn = _\wrap($fn, $wrapper);

        $this->assertEquals($arg, $newFn($arg));
    }

    function testWrapFunctionGivenAsString()
    {
        $wrapper = function($original, $string) {
            return strtoupper($original($string));
        };
        $newFn = _\wrap("\Underscore\camelize", $wrapper);

        $this->assertEquals("FOOBARBAZ", $newFn("foo_bar_baz"));
    }

    function testCurry()
    {
        $multiply = function($x, $y) {
            return $x * $y;
        };

        $double = _\curry($multiply, 2);

        $this->assertEquals(2, $double(1));
    }

    function testCompose()
    {
        $greet = function($name) {
            return "Hello $name";
        };

        $exclaim = function($statement) {
            return $statement . "!";
        };

        $greetAndExclaim = _\compose($greet, $exclaim);

        $this->assertEquals("Hello World!", $greetAndExclaim("World"));
    }

    /**
     * @dataProvider arrayProvider
     */
    function testArrayDeleteKey($array)
    {
        $value = _\deleteKey("foo", $array);

        $this->assertEquals("bar", $value);
        $this->assertEmpty($array);
    }

    /**
     * @dataProvider arrayProvider
     */
    function testArrayDeleteValue($array)
    {
        $value = _\delete("bar", $array);

        $this->assertEquals("bar", $value);
        $this->assertEmpty($array);
    }

    /**
     * @dataProvider arrayProvider
     */
    function testArrayDeleteAndKeyNotFound($array)
    {
        $value = _\deleteKey("notexistingkey", $array);

        $this->assertNull($value);
    }

    /**
     * @dataProvider arrayProvider
     */
    function testArrayDeleteAndValueNotFound($array)
    {
        $value = _\delete("notexistingvalue", $array);

        $this->assertNull($value);
    }

    function arrayProvider()
    {
        return array(
            array(array("foo" => "bar")),
        );
    }
}

