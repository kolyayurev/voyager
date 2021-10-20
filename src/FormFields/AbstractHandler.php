<?php

namespace TCG\Voyager\FormFields;

use View;

use Illuminate\Support\Str;
use TCG\Voyager\Traits\Renderable;

abstract class AbstractHandler implements HandlerInterface
{
    use Renderable;

    protected $name;
    protected $viewEdit;
    protected $viewRead ;
    protected $viewBrowse ;
    protected $viewDefaultEdit   = 'voyager::formfields.text.edit';
    protected $viewDefaultRead   = 'voyager::formfields.text.read';
    protected $viewDefaultBrowse = 'voyager::formfields.text.browse';
    //TODO: vue alternative
    protected $codename;
    protected $supports = [];

    public function handle($row, $dataType, $dataTypeContent,$type)
    {
        $content = $this->createContent(
            $row,
            $dataType,
            $dataTypeContent,
            $row->details,
            $type
        );
        return $this->render($content);
    }

    public function supports($driver)
    {
        if (empty($this->supports)) {
            return true;
        }

        return in_array($driver, $this->supports);
    }

    public function getCodename()
    {
        if (empty($this->codename)) {
            $name = class_basename($this);

            if (Str::endsWith($name, 'Handler')) {
                $name = substr($name, 0, -strlen('Handler'));
            }

            $this->codename = Str::snake($name);
        }

        return $this->codename;
    }

    public function getName()
    {
        if (empty($this->name)) {
            $this->name = ucwords(str_replace('_', ' ', $this->getCodename()));
        }

        return $this->name;
    }
    protected function checkView($view)
    {
        return  View::exists($view);
    }
    protected function getViewByType($type)
    {
        $view = '';
        switch ($type) {
            case 'edit':
                $view = $this->checkView($this->viewEdit)?$this->viewEdit:$this->viewDefaultEdit;
                break;
            case 'browse':
                $view = $this->checkView($this->viewBrowse)?$this->viewBrowse:$this->viewDefaultBrowse;
                break;
            case 'read':
                $view = $this->checkView($this->viewRead)?$this->viewRead:$this->viewDefaultRead;
                break;
        }
        return $view;
    }
    public function createContent($row, $dataType, $dataTypeContent, $options, $type)
    {
        return view($this->getViewByType($type), compact(['row','options','dataType','dataTypeContent']));
    }
}
