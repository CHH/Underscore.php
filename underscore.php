<?php
/**
 * Utility Functions
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @package    Underscore.php
 * @author     Christoph Hochstrasser <christoph.hochstrasser@gmail.com>
 * @copyright  Copyright (c) Christoph Hochstrasser
 * @license    MIT License
 */

namespace 
{
    function _c($collection) {
        return new Underscore\Collection($collection);
    }
}

/** @namespace */
namespace Underscore
{

    function collection($collection)
    {
        return new Collection($collection);
    }

    class Collection implements \ArrayAccess, \IteratorAggregate, \Countable
    {
        /** @var array */
        protected $value;    
        
        function __construct($value = array())
        {
            if ($value instanceof \Traversable) {
                $value = iterator_to_array($value);
            }
            $this->value = $value;
        }
        
        function __call($method, array $args)
        {
            array_unshift($args, $this->value);
            $this->value = call_user_func_array(__NAMESPACE__ . '\\' . $method, $args);
            return $this;
        }
        
        function range($start, $stop, $step = 1)
        {
            $this->value = range($start, $stop, $step);
            return $this;
        }
        
        function shift()
        {
            return array_shift($this->value);
        }
        
        function unshift($value)
        {
            array_unshift($this->value, $value);
            return $this;
        }
        
        function pop()
        {
            return array_pop($this->value);
        }
        
        function push($value)
        {
            $this->value[] = $value;
            return $this;
        }
        
        function value()
        {
            return $this->value;
        }
        
        function count()
        {
            return count($this->value);
        }
        
        function getIterator()
        {
            return new ArrayIterator($this->value);
        }
        
        function offsetGet($offset)
        {
            return $this->value[$offset];
        }
        
        function offsetSet($offset, $value)
        {
            $this->value[$offset] = $value;
        }
        
        function offsetExists($offset)
        {
            return isset($this->value[$offset]);
        }
        
        function offsetUnset($offset)
        {
            unset($this->value[$offset]);
        }
    }

    function each($list, $iterator)
    {
        foreach ($list as $key => $value) {
            call_user_func($iterator, $value, $key);
        }
    }

    function map($list, $iterator)
    {
        $return = array();
        
        foreach ($list as $key => $value) {
            $return[$key] = call_user_func($iterator, $value, $key);
        }
        return $return;
    }

    function reduce($list, $iterator, $memo)
    {
    }

    function detect($list, $iterator)
    {
        foreach ($list as $key => $value) {
            if (true === (bool) call_user_func($iterator, $value, $key)) {
                return $value;
            }
        }
        return false;
    }

    function select($list, $iterator)
    {
        $return = array();
        
        foreach ($list as $key => $value) {
            if (true === (bool) call_user_func($iterator, $value, $key)) {
                $return[$key] = $value;
            }
        }
        return $return;
    }

    function reject($list, $iterator)
    {
        $return = array();
        
        foreach ($list as $key => $value) {
            if (true !== (bool) call_user_func($iterator, $value, $key)) {
                $return[$key] = $value;
            }
        }
        return $return;
    }

    function all($list, $iterator)
    {
        $valid = true;
        
        foreach ($list as $key => $value) {
            $valid = (bool) call_user_func($iterator, $value, $key);
        }
        return $valid;
    }

    function any($list, $iterator)
    {
        return detect($list, $iterator) ? true : false;
    }

    function includes($list, $value)
    {
        if (is_array($list)) {
            return indexOf($list, $value) ? true : false;
        }
        foreach ($list as $value) {
            if ($v === $value) {
                return true;
            }
        }
        return false;
    }

    function invoke($list, $method/*, $arg,... */)
    {
        $args = array_slice(func_get_args(), 2);
        
        foreach ($list as $object) {
            if (is_callable($object, $method)) {
                call_user_func_array(array($object, $method), $args);
            }
        }
        return $list;
    }

    function pluck($list, $property)
    {
        $values = array();
        foreach ($list as $object) {
            if (empty($object->{$property})) {
                $value = null;
            } else {
                $value = $object->{$property};
            }
            $values[] = $value;
        }
        return $value;
    }

    function max($list, $iterator = null)
    {
    }

    function min($list, $iterator = null)
    {
    }

    function sortBy($list, $iterator)
    {
    }

    function size($list)
    {
        if ($list instanceof \Traversable and !$list instanceof \Countable) {
            return iterator_count($list);
        }
        return count($list);
    }

    /**
     * Splits the string on spaces and returns the parts
     * 
     * @param  string $string
     * @return array
     */
    function words($string)
    {
        return explode(" ", (string) $string);
    }

    /**
     * Deletes the given key from the array and returns his value
     *
     * @param  mixed $key   Key to search for
     * @param  array $array
     * @return mixed Value of the given key, NULL if key was not found in array
     */
    function deleteKey($key, &$array)
    {
        if (!isset($array[$key])) {
            return null;
        }
        $value = $array[$key];
        unset($array[$key]);
        return $value;
    }

    /**
     * Searches the given value in the array, unsets the found offset
     * and returns the value
     *
     * @param  mixed $value Value to search for
     * @param  array $array
     * @return mixed The value or NULL if the value was not found
     */
    function delete($value, &$array)
    {
        $offset = array_search($value, (array) $array);
        if (false === $offset) {
            return null;
        }
        unset($array[$offset]);
        return $value;
    }

    function compact($array)
    {
        return array_filter((array) $array);
    }

    function flatten($array)
    {
    }

    function without($array, $value)
    {
        $return = array();
        
        foreach ($array as $v) {
            if ($value !== $v) {
                $return[] = $v;
            }
        }
        return $return;
    }

    function uniq($array)
    {
        return array_unique($array);
    }

    // TODO: Implement Algorithm
    function zip(/* $array,... */)
    {
        $arrays = func_get_args();
        $return = array();
        
    }

    function indexOf($array, $value)
    {
        $index = array_search($value, $array);
        return $index ?: -1;
    }

    function intersect(/* $array,... */)
    {
        $arrays = func_get_args();
        return call_user_func_array("array_intersect", $arrays);
    }

    function first($array)
    {
        $copy = $array;
        return array_shift((array) $copy);
    }

    function last($array)
    {
        $copy = $array;
        return array_pop((array) $copy);
    }

    /*
     * Function functions
     */

    function times($number, $callback, array $args = array())
    {
        for ($i = 0; $i < $number; $i++) {
            call_user_func_array($callback, $args);
        }
    }

    function identity()
    {
        return function($k) { return $k; };
    }

    /**
     * Wrap a function in another function and avoid a recursion by passing 
     * the wrapped function as argument to the wrapper
     *
     * @param  callback $fn      The function to wrap
     * @param  callback $wrapper A wrapper function, receives the wrapped function as
     *                           first argument and the arguments passed to the wrapped 
     *                           function as subsequent arguments
     * @return Closure
     */
    function wrap($fn, $wrapper)
    {
        // Unify calling of the wrapped function
        if(is_array($fn) or is_string($fn)) {
            $original = function() use ($fn) {
                return call_user_func_array($fn, func_get_args());
            };
        } else {
            $original = $fn;
        }
        
        $wrapped = function() use ($original, $wrapper) {
            $args = func_get_args();
            array_unshift($args, $original);
            return call_user_func_array($wrapper, $args);
        };
        
        return $wrapped;
    }

    /**
     * Prefills the arguments of a given function
     *
     * @param  callback $fn        Function to curry
     * @param  mixed    $value,... Arguments for currying the function
     * @return Closure
     */
    function curry($fn)
    {
        $curry = array_slice(func_get_args(), 1);
        
        return function() use ($fn, $curry) {
            $args = array_merge($curry, func_get_args());
            return call_user_func_array($fn, $args);
        };
    }

    /**
     * Composes multiple callback functions into one by passing each function's
     * return value as argument into the next function. The arguments passed to
     * the composed function get passed to the first (most inner) function.
     *
     * @param  callback $fn,... Functions to compose
     * @return Closure
     */
    function compose()
    {
        $fns = func_get_args();
        
        return function() use ($fns) {
            $input = func_get_args();
            foreach ($fns as $fn) {
                $returnValue = call_user_func_array($fn, $input);
                $input = array($returnValue);
            }
            return $returnValue;
        };
    }

    /**
     * Calls the Setter Methods in the given object context for every key
     * in the supplied options. The Name of the Setter Method must be camelCased
     * and the key in the $options Array must have underscores  
     * e.g. for the key "file_name" the Setter's name is "setFileName".
     *
     * @throws InvalidArgumentException If no object is given as context
     *
     * @param  object $context The object context in which the Setters get called
     * @param  array  $options Array containing key => value pairs
     * @param  array  $settableOptions Optional list of fields which are settable 
     *                                on the object
     * @return bool  true if some options have been set in the context, false if no
     *               options were set
     */
    function setOptions($context, array $options, array $defaults = array())
    {
        if (!is_object($context)) {
	        throw new \InvalidArgumentException("Context for setting options is not an Object");
        }
        if (!$options) {
	        return false;
        }

        if ($defaults) {
	        $options = array_merge($defaults, $options);
        }

        foreach ($options as $key => $value) {
	        $setterName = "set" . camelize($key);
	
	        if   (!is_callable(array($context, $setterName))) continue;
	        else $context->{$setterName}($value);
        }
        return true;
    }

    /**
     * Camelizes a dash or underscore separated string
     *
     * @param  string $string
     * @param  bool   $pascalCase By default the first letter is uppercase
     * @return string
     */
    function camelize($string, $pascalCase = true)
    {
        $string = str_replace(array("-", "_"), " ", $string);
        $string = ucwords($string);
        $string = str_replace(" ", null, $string);
        
        if (!$pascalCase) {
            return lcfirst($string);
        }
        return $string;
    }
}
