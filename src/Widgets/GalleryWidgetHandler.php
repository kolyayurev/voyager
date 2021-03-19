<?php

namespace TCG\Voyager\Widgets;

use Illuminate\Http\Request;

class GalleryWidgetHandler extends BaseWidgetHandler
{

    
    public function __construct()
    {
        $this->view = 'voyager::widgets.widgets.gallery';
        $this->name = 'gallery';
        $this->codename = 'gallery';
    }

    public function handleRequest(Request $request)
    {
        dd($request);
    }
}
