<?php

require "underscore.php";

class UnderscoreTest extends \PHPUnit_Framework_TestCase
{
    function testCamelize()
    {
        $string1 = "foo-bar-baz";
        $string2 = "foo_bar_baz";
        
        $expect  = "FooBarBaz";
        
        $this->assertEquals($expect, _\camelize($string1));
        $this->assertEquals($expect, _\camelize($string2));
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
        $newFn = _\wrap("_\camelize", $wrapper);
        
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
    
    function testBlockGiven()
    {
        $fn = function($block) {
            return _\block_given(func_get_args());
        };
        
        $this->assertTrue($fn(function() {}));
    }
    
    function testBlockGivenAtOffset()
    {
        $fn = function($a, $block, $c) {
            return _\block_given(func_get_args(), 1);
        };
        
        $this->assertTrue($fn("a", function() {}, "b"));
    }
    
    /**
     * @dataProvider arrayProvider
     */
    function testArrayDeleteKey($array)
    {
        $value = _\delete_key("foo", $array);
        
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
        $value = _\delete_key("notexistingkey", $array);
        
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
