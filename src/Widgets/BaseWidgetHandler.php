<?php

namespace TCG\Voyager\Widgets;

use Illuminate\Http\Request;


abstract class BaseWidgetHandler
{

    protected $view;

    abstract public function getView();

    abstract public function handle(Request $request);
  
}
