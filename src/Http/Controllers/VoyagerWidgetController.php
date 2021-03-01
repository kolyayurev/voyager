<?php

namespace TCG\Voyager\Http\Controllers;

use Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\Permission;


class VoyagerWidgetController extends VoyagerBaseController
{

    public function builder($id)
    {
        // Check permission
        $this->authorize('edit', Voyager::model('Widget'));

        $data = Voyager::model('Widget')->findOrFail($id);

        $handler = $data->getHandler();

        dd( $handler);
        if (!view()->exists($view)) {
            return redirect()->route('voyager.widgets.index')->with([
                'message'    => __('voyager::generic.view_not_found'),
                'alert-type' => 'error',
            ]);
        }
        return Voyager::view($view,compact('data'));
    }
    public function builder_store($id)
    {
        // Check permission
        $this->authorize('edit', Voyager::model('Widget'));

        $data = Voyager::model('Widget')->get();

        return Voyager::view('voyager::settings.index');
    }
}