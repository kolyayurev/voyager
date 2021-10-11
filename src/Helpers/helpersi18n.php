<?php

if (!function_exists('__')) {
    function __($key, array $par = [])
    {
        return trans($key, $par);
    }
}
if (!function_exists('vtrans')) {
    function vtrans($key, array $par = [])
    {
        return trans('voyager::'.$key, $par);
    }
}
