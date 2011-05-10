<?php

namespace Underscore\Test;

use Underscore as _;

class ArrayTest extends \PHPUnit_Framework_TestCase
{
    function testCanPullOutTheFirstElementOfAnArray()
    {
        $this->assertEquals(1, _\first(array(1, 2, 3)));
    }
    
    /**
     * @testdox Can perform OO-style "first()"
     */
    function testCanPerformOOStyleFirst()
    {
        $this->assertEquals(1, _\from(array(1, 2, 3))->first()->value());
    }
    
    function testCanPassAnIndexToFirst()
    {
        $list = array(1, 2, 3);
        $this->assertEquals(array(), _\first($list, 0));
        $this->assertEquals(array(1, 2), _\first($list, 2));
    }
    
    /**
     * @testdox Working rest()
     */
    function testWorkingRest()
    {
        $numbers = array(1, 2, 3, 4);
        $this->assertEquals("2, 3, 4", join(_\rest($numbers), ", "));
    }
    
    /**
     * @testdox Working rest(0)
     */
    function testWorkingRestWith0()
    {
        $numbers = array(1, 2, 3, 4);
        $this->assertEquals($numbers, _\rest($numbers, 0));
    }
    
    function testCanPullOutTheLastElementOfAnArray()
    {
        $numbers = array(1, 2, 3);
        $this->assertEquals(3, _\last($numbers));
    }
    
    function testCanTrimOutAllFalsyValues()
    {
        $values = array(0, 1, false, 2, false, 3);
        $this->assertEquals(3, sizeof(_\compact($values)));
    }
    
    function testCanFlattenNestedArrays()
    {
        $list = array(1, array(2), array(3, array(array(array(4)))));
        $this->assertEquals("1, 2, 3, 4", join(_\flatten($list), ", "));
    }
    
    function testCanRemoveAllInstancesOfAnObject()
    {
        $list = array(1, 2, 1, 0, 3, 1, 4);
        $this->assertEquals("2, 3, 4", join(_\without($list, 0, 1), ", "));
    }
    
    function testUsesRealObjectIdentityForComparisons()
    {
        $list = array(
            (object) array("one" => 1), (object) array("two" => 2)
        );
        
        $this->assertTrue(sizeof(_\without($list, (object) array("one" => 1))) == 2);
        $this->assertTrue(sizeof(_\without($list, $list[0])) == 1);
    }
    
    function testCanFindTheUniqueValuesOfAnUnsortedArray()
    {
        $list = array(1, 2, 1, 3, 1, 4);
        $this->assertEquals("1, 2, 3, 4", join(", ", _\uniq($list)));
    }
    
    function testCanFindTheUniqueValuesOfAnSortedArrayFaster()
    {
        $list = array(1, 1, 1, 2, 2, 3);
        $this->assertEquals("1, 2, 3", join(", ", _\uniq($list, true)));
    }
}
