<?php

namespace TCG\Voyager\Http\Controllers;

use Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Events\BreadDataUpdated;


class VoyagerWidgetController extends VoyagerBaseController
{

    public function moderate($id)
    {
        // Check permission
        $this->authorize('moderate', Voyager::model('Widget'));

        $dataType = Voyager::model('DataType')->where('slug', 'widgets')->first();

        $dataTypeContent = Voyager::model('Widget')->findOrFail($id);

        return Voyager::widgetDisplay($dataType,$dataTypeContent);
    }

    public function moderate_update(Request $request,$id)
    {
        // Check permission
        $this->authorize('moderate', Voyager::model('Widget'));

        $dataTypeContent = Voyager::model('Widget')->findOrFail($id);

        $data = $dataTypeContent->makeHidden('value')->toArray();
        $handler = Voyager::widgetHandler($dataTypeContent->handler);

        $request->merge($data);
        $request->merge(['value' => $handler->handleValue($request->value)]);

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $data = $model->withTrashed()->findOrFail($id);
        } else {
            $data = $model->findOrFail($id);
        }

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->rows, $dataType->name, $id)->validate();
        $this->insertUpdateData($request, $slug, $dataType->rows, $data);

        event(new BreadDataUpdated($dataType, $data));

        if (auth()->user()->can('browse', app($dataType->model_name))) {
            $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        } else {
            $redirect = redirect()->back();
        }

        return $redirect->with([
            'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);

    }
}