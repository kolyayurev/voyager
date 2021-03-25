<?php

namespace TCG\Voyager\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class WorkTimeHandler extends AbstractHandler
{
    protected $codename = 'work_time';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('formfields.work_time', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}
