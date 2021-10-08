<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return TCG\Voyager\Facades\Voyager::setting($key, $default);
    }
}

if (!function_exists('widget')) {
    function widget($slug, $default = null,$isCollection = false)
    {
        $data =  TCG\Voyager\Facades\Voyager::widgetData($slug, $default);
        return $isCollection?collect($data):$data;
    }
}

if (!function_exists('menu')) {
    function menu($menuName, $type = null, array $options = [])
    {
        return TCG\Voyager\Facades\Voyager::model('Menu')->display($menuName, $type, $options);
    }
}

if (!function_exists('voyager_asset')) {
    function voyager_asset($path, $secure = null)
    {
        return route('voyager.voyager_assets').'?path='.urlencode($path);
    }
}

if (!function_exists('get_file_name')) {
    function get_file_name($name)
    {
        preg_match('/(_)([0-9])+$/', $name, $matches);
        if (count($matches) == 3) {
            return Illuminate\Support\Str::replaceLast($matches[0], '', $name).'_'.(intval($matches[2]) + 1);
        } else {
            return $name.'_1';
        }
    }
}
if (!function_exists('empty_object')) {

    function empty_object($object):bool
    {
        $array = !is_array($object)?(array)$object:$object;
        return empty($array)? true: false;
    }
}
if (!function_exists('array_to_object')) {

    function array_to_object(array $array)
    {

        $object = json_decode(json_encode($array), FALSE);
        
        return $object;
    }
}
if (!function_exists('string_to_object')) {

    function string_to_object($str)
    {
        return array_to_object(json_decode($str));
    }
}
if (!function_exists('printString')) {

    function printString($string,$default="")
    {
        if($string==null || !$string)
            $string=$default;
        return json_encode($string);
    }
}

if (!function_exists('printArray')) {

    function printArray($array,$default=[])
    {
        if($array==null || !$array)
            $array=$default;
        return json_encode($array);
    }
}

if (!function_exists('printObject')) {

    function printObject($obj)
    {
        if($obj==null || !$obj)
            $obj=new stdClass();
        return json_encode($obj);
    }
}
if (!function_exists('printInt')) {

    function printInt($number,$default=null)
    {   
        $number = (int)$number;
        if($number==null || !$number)
            $number=$default;
        return json_encode($number);
    }
}
if (!function_exists('printFloat')) {

    function printFloat($number,$default=null)
    {   
        $number = (float)$number;
        if($number==null || !$number)
            $number=$default;
        return json_encode($number);
    }
}
if (!function_exists('printBool')) {

    function printBool($bool)
    {
        return json_encode($bool ? true : false);
    }
}