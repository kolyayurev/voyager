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

    public function handle($dataType, $dataTypeContent)
    {
        $content = $this->createContent(
            $dataType,
            $dataTypeContent,
            $dataTypeContent->getDetails()
        );

        return $this->render($content);
    }

    public function createContent($dataType, $dataTypeContent, $options)
    {
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        return view($this->getView(), [
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'isModelTranslatable' => $isModelTranslatable
        ]);
    }
  
}
