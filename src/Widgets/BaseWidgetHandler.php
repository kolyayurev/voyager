<?php

namespace TCG\Voyager\Widgets;

use TCG\Voyager\Traits\Renderable;

use Illuminate\Support\Str;
use Illuminate\Http\Request;


abstract class BaseWidgetHandler implements WidgetInterface
{
    use Renderable;

    protected $name;
    protected $view;
    protected $codename;
    protected $default;
    protected $details;

    public function setDetails($details)
    {
        return $this->details = $details;
    }

    public function getView()
    {
        return optional($this->details)->view && !empty($this->details->view) ? $this->details->view :$this->view;
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

    public function handle($dataType, $dataTypeContent)
    {
        $this->setDetails($dataTypeContent->getDetails());

        $content = $this->createContent(
            $dataType,
            $dataTypeContent,
            $this->details
        );

        return $this->render($content);
    }

    public function createContent($dataType, $dataTypeContent, $options)
    {
        return view($this->getView(), [
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }

    public function handleValue($value)
    {
        return $value;
    }
    public function getValue($value, $default = null)
    {
        $default = $default ?? $this->default;

        return $value ?? $default;
    }
  
  
}
