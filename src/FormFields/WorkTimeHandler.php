<?php

namespace TCG\Voyager\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class WorkTimeHandler extends AbstractHandler
{
    protected $viewEdit = 'voyager::formfields.work_time.edit';
    protected $viewRead = 'voyager::formfields.work_time.read';
    protected $viewBrowse = 'voyager::formfields.work_time.browse';
    protected $codename = 'work_time';

}
