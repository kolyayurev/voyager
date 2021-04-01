<?php

if (!function_exists('get_reflection_method')) {
    function get_reflection_method($object, $method)
    {
        $reflectionMethod = new \ReflectionMethod($object, $method);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod;
    }
}

if (!function_exists('call_protected_method')) {
    function call_protected_method($object, $method, ...$args)
    {
        return get_reflection_method($object, $method)->invoke($object, ...$args);
    }
}

if (!function_exists('get_reflection_property')) {
    function get_reflection_property($object, $property)
    {
        $reflectionProperty = new \ReflectionProperty($object, $property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty;
    }
}

if (!function_exists('get_protected_property')) {
    function get_protected_property($object, $property)
    {
        return get_reflection_property($object, $property)->getValue($object);
    }
}

if (! function_exists('class_has_trait')) {
    /**
     * class_has_trait
     *
     * @param  mixed $class Class name
     * @param  mixed $trait Trait name
     * @return bool
     */

    function class_has_trait(?string $class,?string $trait):bool
    {
        return in_array($trait,array_keys((new \ReflectionClass($class))->getTraits()));       
    }
}
if (! function_exists('model_has_states')) {
    function model_has_widgets(?string $class):bool
    {
        return class_has_trait($class,\TCG\Voyager\Traits\Widgetable::class);
    }
}