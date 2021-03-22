<?php

namespace TCG\Voyager\Http\Controllers;

use Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\Permission;


class VoyagerWidgetController extends VoyagerBaseController
{

    public function moderate($id)
    {
        // Check permission
        $this->authorize('moderate', Voyager::model('Widget'));

        $dataType = Voyager::model('DataType')->where('slug', 'widgets')->first();

        $dataTypeContent = Voyager::model('Widget')->findOrFail($id);


        // $view = Voyager::widgetHandlerView($dataTypeContent->handler);

        // if (!view()->exists($view)) {
        //     return redirect()->route('voyager.widgets.index')->with([
        //         'message'    => __('voyager::generic.view_not_found'),
        //         'alert-type' => 'error',
        //     ]);
        // }

        return Voyager::widget($dataType,$dataTypeContent);
    }

    public function moderate_update(Request $request,$id)
    {
        // Check permission
        $this->authorize('moderate', Voyager::model('Widget'));

        $dataTypeContent = Voyager::model('Widget')->findOrFail($id);

        $request->merge(['value' => '']);

        $this->update($request,$id);

    }
}