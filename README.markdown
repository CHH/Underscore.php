Underscore.php -- a PHP Port of Underscore.js
=============================================

## Requirements

Underscore.php puts all its functions into the `Underscore` Namespace,
so it requires at least PHP 5.3.0.

## Main differences to Underscore.js

 * No Support for timer based functions (delay, debounce, defer,...) as they
   make no sense in a synchronous environment, such as Requests to PHP
 * `_()` is `__()`, because `_()` is already taken by the `gettext` Extension
 * `include()` is `includes()`, because `include` is a reserverd word in PHP

## Usage

Everything you need for using Underscore.php is contained in the file 
`underscore.php` in the projects repository. Just put this file somewhere
in your path and include the file in your code with `require_once "underscore.php"`.
All functions are in contained in the `Underscore` Namespace. You may want to alias
this Namespace to `_`, by calling `use Underscore as _;`. 
Calls to functions then will look like this: `$result = _\first(array(1, 2, 3));`.

## License

Underscore.php is MIT licensed. Please see the included `LICENSE.txt`.
