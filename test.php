<?php

namespace Underscore;

require "underscore.php";

use Underscore as _;

class MockMixin extends Mixable
{
    function someRealMethod()
    {}
}

class Test extends \PHPUnit_Framework_TestCase
{
    function testMixable()
    {
        $mixable = new MockMixin;
        $mixable->_def("greet", function($self, $name) {
            echo "Hello " . $name;
        });

        $this->assertTrue($mixable->_respondsTo("greet"));
        $this->assertTrue($mixable->_respondsTo("someRealMethod"));
    }

    function testChaining()
    {
        $value = _c(array("foo", "bar", "baz"))->map(function($word) {
            return strtoupper($word);
        })->value();

        $this->assertEquals(array("FOO", "BAR", "BAZ"), $value);
    }

    function testCamelize()
    {
        $string1 = "foo-bar-baz";
        $string2 = "foo_bar_baz";

        $expect  = "FooBarBaz";

        $this->assertEquals($expect, _\camelize($string1));
        $this->assertEquals($expect, _\camelize($string2));
    }

    function testMap()
    {
        $data = array("foo", "bar", "baz");
        $expected = array("FOO", "BAR", "BAZ");

        $mapped = _\map($data, "strtoupper");

        $this->assertEquals($expected, $mapped);
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

