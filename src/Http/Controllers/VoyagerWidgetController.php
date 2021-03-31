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

        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        return view('voyager::widgets.widgets.index',compact(['isModelTranslatable','dataType','dataTypeContent']));
    }

    public function moderate_update(Request $request,$id)
    {
        // dd($request->all());
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

        // if(!$request->has('from_model'))
        // {
        //     if (auth()->user()->can('browse', app($dataType->model_name))) {
        //         $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        //     } else {
        //         $redirect = redirect()->back();
        //     }

        //     return $redirect->with([
        //         'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
        //         'alert-type' => 'success',
        //     ]);
        // }

        return  response()->json([
                'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                'status' => 'success',
        ]);

    }
    public function get_data_type_content_items(Request $request)
    {
        $this->authorize('edit', Voyager::model('Widget'));

        // $dataTypeContent = app($dataType->model_name)->get();

        $search = $request->search ?? false;

        $dataType = Voyager::model('DataType')->where('slug', '=', $request->data_type)->first();

        $options = $dataType->details;

        $search_field = $dataType->default_widget_search_key;

        $model = app($dataType->model_name);

        $additional_attributes = $model->additional_attributes ?? [];
        
        foreach ($dataType->rows as $key => $row) {
            if ($row->field === $search_field) {

                // If search query, use LIKE to filter results depending on field label
                if ($search) {
                    // If we are using additional_attribute as label
                    if (in_array($search_field, $additional_attributes)) {
                        $dataTypeContent = $model->all();
                        $dataTypeContent = $dataTypeContent->filter(function ($model) use ($search) {
                            return stripos($model->{$search_field}, $search) !== false;
                        });
                    } else {
                        $dataTypeContent = $model->where($search_field, 'LIKE', '%'.$search.'%')->get();
                    }
                   
                } else {
                    $dataTypeContent = $model->get();
                }

                $total_count = $dataTypeContent->count();

                $results = [];

                if (!$search) {
                    $results[] = [
                        'id'   => '',
                        'text' => __('voyager::generic.none'),
                    ];
                }

                foreach ($dataTypeContent as $data) {
                    $results[] = [
                        'id'   => $data->getKey(),
                        'text' => $data->{$search_field},
                    ];
                } 

                return  response()->json([
                    'results' => $results,
                    'status' => 'success',
                ]);
            }
        }
        // No result found, return empty array
        return response()->json([], 404);
    }
}
    