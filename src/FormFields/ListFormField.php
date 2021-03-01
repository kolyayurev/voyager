<?php

namespace TCG\Voyager\FormFields;


class ListFormField extends AbstractHandler
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