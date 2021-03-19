<?php

namespace TCG\Voyager\Widgets;

use Illuminate\Http\Request;

interface WidgetInterface
{
    public function getView();

    public function getCodename();

    public function handleRequest(Request $request);
}
