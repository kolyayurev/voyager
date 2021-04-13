<?php

namespace TCG\Voyager\FormFields;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MediaPickerDialogHandler extends MediaPickerHandler
{
    protected $codename = 'media_picker_dialog';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
       
        [$row,$options,$dataType,$content] = $this->preCreateContent($row, $dataType, $dataTypeContent, $options);
        
        return view('voyager::formfields.media_picker_dialog', [
            'row'      => $row,
            'options'  => $options,
            'dataType' => $dataType,
            'content'  => $content,
        ]);
    }
}
