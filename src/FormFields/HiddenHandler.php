<?php

namespace TCG\Voyager\FormFields;

class HiddenHandler extends AbstractHandler
{
    protected $viewEdit = 'voyager::formfields.hidden.edit';
    protected $viewRead = 'voyager::formfields.hidden.read';
    protected $viewBrowse = 'voyager::formfields.hidden.browse';
    protected $codename = 'hidden';

}
