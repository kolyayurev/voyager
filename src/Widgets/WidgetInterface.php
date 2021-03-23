<?php

namespace TCG\Voyager\Widgets;

use Illuminate\Http\Request;

interface WidgetInterface
{

    public function getView();

    public function getCodename();

    public function handleValue($value);

    public function getValue($value,$default);

    public function handle($dataType, $dataTypeContent);

    public function createContent($dataType, $dataTypeContent, $options);

}
