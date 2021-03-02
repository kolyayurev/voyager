<?php

namespace TCG\Voyager\Widgets;

use Illuminate\Http\Request;

class GalleryWidgetHandler extends BaseWidgetHandler
{

    public function __construct()
    {
        $this->view ='voyager::widgets.widgets.gallery';
    }

    public function getView()
    {
        return $this->view;
    }

    public function handle(Request $request)
    {
        dd($request);
    }
}
