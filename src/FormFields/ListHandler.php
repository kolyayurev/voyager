<?php

namespace TCG\Voyager\FormFields;

/**
 * Model must cast field to JsonCast
 */

class ListHandler extends AbstractHandler
{
    protected $codename = 'list';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('voyager::formfields.list', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}