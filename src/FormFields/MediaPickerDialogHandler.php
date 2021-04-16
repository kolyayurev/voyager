<?php

namespace TCG\Voyager\FormFields;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MediaPickerDialogHandler extends MediaPickerHandler
{
    protected $viewEdit = 'voyager::formfields.media_picker_dialog';
    protected $codename = 'media_picker_dialog';

    public function createContent($row, $dataType, $dataTypeContent, $options, $type)
    {
        [$row,$options,$dataType,$content] = $this->preCreateContent($row, $dataType, $dataTypeContent, $options);
        
        return view($this->getViewByType($type), [
            'row'      => $row,
            'options'  => $options,
            'dataType' => $dataType,
            'content'  => $content,
        ]);
    }
}
