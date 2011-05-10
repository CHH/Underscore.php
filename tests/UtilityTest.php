<?php

namespace Underscore\Test;

use Underscore as _;

class UtilityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testdox Provides __() shortcut function
     */
    function testProvidesShortCutFunction()
    {
        $this->assertTrue(is_callable("\\__"));
    }
}
