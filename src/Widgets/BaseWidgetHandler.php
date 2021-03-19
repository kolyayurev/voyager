<?php

namespace TCG\Voyager\Widgets;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

abstract class BaseWidgetHandler implements WidgetInterface
{

    protected $name;
    protected $view;
    protected $codename;

    public function getView()
    {
        return $this->view;
    }

    public function getCodename()
    {
        if (empty($this->codename)) {
            $name = class_basename($this);

            if (Str::endsWith($name, 'WidgetHandler')) {
                $name = substr($name, 0, -strlen('WidgetHandler'));
            }

            $this->codename = Str::snake($name);
        }

        return $this->codename;
    }

    public function getName()
    {
        if (empty($this->name)) {
            $this->name = $this->getCodename();
        }

        return Str::title($this->name);
    }
  
}
