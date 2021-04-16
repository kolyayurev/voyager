<?php

namespace TCG\Voyager\FormFields;

class CheckboxHandler extends AbstractHandler
{
    protected $viewEdit = 'voyager::formfields.checkbox.edit';
    protected $viewRead = 'voyager::formfields.checkbox.read';
    protected $viewBrowse = 'voyager::formfields.checkbox.browse';
    protected $codename = 'checkbox';
}
