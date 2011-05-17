<?php

namespace Underscore\Test;

use PHPUnit_Framework_Assert as a,
    Underscore as _;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    function testEach()
    {
        _\each(array(1, 2, 3), function($num, $i) {
            a::assertEquals($i + 1, $num);
        });

        $answers = array();
        $obj = (object) array("one" => 1, "two" => 2, "three" => 3);
        _\each($obj, function($value, $key) use (&$answers) {
            $answers[] = $key;
        });
        $this->assertEquals("one, two, three", join(", ", $answers));

        $answer = null;
        _\each(array(1, 2, 3), function($num, $index, $arr) use (&$answer) {
            if (_\contains($arr, $num))
                $answer = true;
        });
        $this->assertTrue(
            $answer, 
            "Can reference the original collection from inside the iterator"
        );

        $answers = 0;
        _\each(null, function() use (&$answers) { ++$answers; });
        $this->assertEquals(0, $answers, "Handles a null properly");
    }

    function testMap()
    {
        $doubled = _\map(array(1, 2, 3), function($num) { return 2 * $num; });
        $this->assertEquals("2, 4, 6", join(", ", $doubled));
        
        $doubled = __(array(1, 2, 3))->map(function($num) {
            return 2 * $num;
        });

        $this->assertEquals("2, 4, 6", join(", ", $doubled));
    }
        
    function testReduce()
    {
        $sum = _\reduce(array(1, 2, 3), function($sum, $num) {
            return $sum + $num;
        }, 0);
        $this->assertEquals(6, $sum, "Can su up an array");

        $sum = _\inject(array(1, 2, 3), function($sum, $num) {
            return $sum + $num;
        }, 0);
        $this->assertEquals(6, $sum, 'Aliased as "inject"');

        $sum = __(array(1, 2, 3))->reduce(function($sum, $num) {
            return $sum + $num;
        }, 0);
        $this->assertEquals(6, $sum, "OO-style reduce");

        $sum = _\reduce(array(1, 2, 3), function($sum, $num) {
            return $sum + $num;
        });
        $this->assertEquals(6, $sum, "default initial value");
    }

    function testDetect()
    {
        $result = _\detect(array(1, 2, 3), function($num) {
            return $num * 2 == 4;
        });
        $this->assertEquals(
            2, $result, 'Found the first "2" and broke the loop'
        );
    }
}
