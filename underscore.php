<?php
/**
 * Library of Utility Functions in the Spirit of Underscore.js
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @package    Underscore.php
 * @author     Christoph Hochstrasser <christoph.hochstrasser@gmail.com>
 * @copyright  Copyright (c) Christoph Hochstrasser
 * @license    MIT License
 */

// Define __() Utility function in global Namespace
namespace 
{
    if (!function_exists('__')) {
        function __($value)
        {
            return \Underscore\chain($value);
        }
    }
}

/** @namespace */
namespace Underscore
{
    use InvalidArgumentException, ArrayAccess, IteratorAggregate, Countable;

    /**
     * Returns a chainable represenation of the given value
     *
     * @param  mixed $value
     * @return Collection
     */
    function chain($value)
    {
        return new Chain($value);
    }

    function from($value)
    {
        return chain($value);
    }

    function perform($value)
    {
        return chain($value);
    }
    
    class Chain implements \ArrayAccess, \IteratorAggregate, \Countable
    {
        /** @var array */
        protected $value;

        function __construct($value = array())
        {
            $this->value = $value;
        }

        /**
         * Forwards calls to the underscore functions and passes the value
         * of the wrapped object as first argument
         *
         * @param  string $method
         * @param  array  $args
         * @return Chain
         */
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

        /**
         * Implement Countable
         *
         * @return int
         */
        function count()
        {
            return count($this->value);
        }

        /**
         * Implement IteratorAggregate
         */
        function getIterator()
        {
            return new ArrayIterator($this->value);
        }

        /*
         * Implement ArrayAccess
         */

        function offsetGet($offset)
        {
            return $this->value[$offset];
        }

        function offsetSet($offset, $value)
        {
            if (null === $offset) 
                $this->push($value);
            else 
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

    /**
     * Does nothing with the value, only passes it to a callback function
     * Can be used to inspect chains.
     *
     * @param mixed $value Value to inspect
     * @param callback $callback
     * @return mixed The given value
     */
    function tap($value, $callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException(sprintf(
                "%s expects a Callback as second argument, %s given",
                __FUNCTION__, gettype($callback)
            ));
        }
        call_user_func($callback, $value);
        return $value;
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
        return array_reduce((array) $list, $iterator, $memo);
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
        return array_filter((array) $list, $iterator);
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

    /**
     * Iterates over the list of objects, reads the given property from each Object
     * and returns the collected values as list
     *
     * @param array $list List of Objects
     * @param string $property Name of the Object Property
     * @return array
     */
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
    function w($string)
    {
        return explode(" ", (string) $string);
    }

    /**
     * Deletes the given key from the array and returns his value
     *
     * @param  array $array
     * @param  mixed $key   Key to search for
     * @return mixed Value of the given key, NULL if key was not found in array
     */
    function deleteKey(&$array, $key)
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
     * @param  array $array
     * @param  mixed $value Value to search for
     * @return mixed The value or NULL if the value was not found
     */
    function delete(&$array, $value)
    {
        $offset = array_search($value, (array) $array);
        if (false === $offset) {
            return null;
        }
        unset($array[$offset]);
        return $value;
    }

    /**
     * Returns the array without all falsy values
     *
     * @param  array $array
     * @return array
     */
    function compact($array)
    {
        return array_filter((array) $array);
    }

    function flatten($array)
    {
        return $array;
    }
    
    /**
     * Returns a copy of the array with all occurences of $value removed
     *
     * @param  array $array
     * @param  mixed $value,...
     * @return array
     */
    function without($array/*, $value,... */)
    {
        $return = array();
        $values = array_slice(func_get_args(), 1);

        foreach ($array as $key => $v) {
            if (!in_array($v, $values, true)) {
                $return[$key] = $v;
            }
        }
        return $return;
    }

    /**
     * Returns a duplicate free version of the array
     *
     * @param array $array
     * @return array
     */
    function uniq($list, $sorted = false)
    {
        return array_unique((array) $list, $sorted ? false : SORT_REGULAR);
    }

    function zip(/* $array,... */)
    {
        return array();
    }

    /**
     * Searches the value in the array and returns its position
     *
     * @param  array $array
     * @param  mixed $value
     * @return mixed Index of the element or -1 if it was not found
     */
    function indexOf($array, $value)
    {
        if (null === $array)
            return -1;
        
        $index = array_search($value, (array) $array);
        return $index ?: -1;
    }

    /**
     * Returns the intersection of all given arrays
     *
     * @see \array_intersect
     * @return array
     */
    function intersect(/* $array,... */)
    {
        // It may look silly, but call_user_func is still dead slow as of PHP 5.3
        if (2 === func_num_args()) {
            list($a1, $a2) = func_get_args();
            return array_intersect($a1, $a2);
        }

        $arrays = func_get_args();

        foreach ($arrays as &$array) {
            $array = (array) $array;
        }

        return call_user_func_array("array_intersect", $arrays);
    }

    /**
     * Returns the first element of the array
     *
     * @return mixed
     */
    function first($list, $length = 1)
    {
        $list = (array) $list;
        return $length === 1 ? array_shift($list) : array_slice($list, 0, $length);
    }

    /**
     * Returns the last element of the array
     *
     * @return mixed
     */
    function last($list)
    {
        $list = (array) $list;
        return array_pop($list);
    }
    
    function rest($list, $size = null)
    {
        $list = (array) $list;
        return array_slice($list, 0 - sizeof($list) - ($size === null ? -1 : $size));
    }
    
    /*
     * Function functions
     */

    /**
     * Calls a supplied callback {n} times
     *
     * @param  int $number
     * @param  callback $callback
     * @param  mixed $arg,...
     * @return mixed Returns the return value of the last call 
     */
    function times($number, $callback/*, $arg,... */)
    {
        $args = array_slice(func_get_args(), 2);

        for ($i = 0; $i < $number; $i++) {
            $return = call_user_func_array($callback, $args);
        }
        return $return;
    }

    /*
     * Function Functions
     * ==================
     */
    
    /**
     * Returns an identity function
     *
     * An identity function is a function which returns it's passed argument unmodified,
     * which is useful for default loop callbacks
     *
     * @return callback
     */
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
        if (is_array($fn)) {
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
     * Allows the supplied function to be called at most once
     * All subsequent calls will return the first call's return value
     *
     * @param  callback $fn
     * @return mixed
     */
    function once($fn)
    {
        return function() use ($fn) {
            static $called = false;
            static $returnValue;

            if ($called) {
                return $returnValue;
            }
            $called = true;
            return $returnValue = call_user_func_array($fn, func_get_args());            
        };
    }

    /**
     * Calls the supplied function after $count calls
     *
     * @param int $count
     * @param callback $fn
     * @return mixed
     */
    function after($count, $fn)
    {
        return function() use ($count, $fn) {
            static $calls = 0;
            static $returnValue;

            $calls++;

            if ($calls == $count) {
                return $returnValue = call_user_func_array($fn, func_get_args());
            }
            return $returnValue;
        };
    }
    
    /**
     * Caches the result of calls with the same arguments
     *
     * @param  callback $fn
     * @return Closure
     */
    function memoize($fn, $hashFunction = null)
    {
        return function() use ($fn, $hashFunction) {
            static $results = array();

            $args = func_get_args();

            $hash = empty($hashFunction)
                ? md5(join($args, ",")) : call_user_func($hashFunction, $args);

            if (empty($results[$hash])) {
                $results[$hash] = call_user_func_array($fn, $args);
            }
            return $results[$hash];
        };
    }

    /**
     * Prefills the arguments of a given function
     *
     * @param  callback $fn        Function to curry
     * @param  mixed    $value,... Arguments for currying the function
     * @return Closure
     */
    function curry($fn/*, $arg,... */)
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
     * Equals to i(f, g, h) = h(g(f(x)))
     *
     * @param  callback $fn,... Functions to compose
     * @return Closure
     */
    function compose(/* $fn,... */)
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

