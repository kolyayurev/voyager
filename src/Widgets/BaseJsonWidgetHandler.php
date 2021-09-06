<?php

namespace TCG\Voyager\Widgets;

use Illuminate\Http\Request;

class BaseJsonWidgetHandler extends BaseWidgetHandler
{
    
    public function __construct()
    {
        $this->view = 'voyager::widgets.widgets.base-json';
        $this->name = 'Base JSON';
        $this->codename = 'base_json';
    }

    public function handleValue($value)
    {
        return !is_string($value)?json_encode($value,JSON_UNESCAPED_UNICODE):$value;
    }

    public function getValue($value, $default = null)
    {
        $default = $default ?? $this->default;

        return (is_string($value)?json_decode($value, FALSE):$value) ?? $default;
    }
}
