<?php

namespace TCG\Voyager\FormFields;


abstract class BaseWidgetHandler
{

    protected $view;

    abstract public function view();

    abstract public function handle();
  
}
