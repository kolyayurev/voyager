{{-- @dd($options) --}}
@if(isset($options->model) && isset($options->type))

    @if(class_exists($options->model))

        @php $relationshipField = $row->field; @endphp

        @if($options->type == 'belongsTo')

            @if(isset($view) && ($view == 'browse' || $view == 'read'))

                @php
                    $relationshipData = (isset($data)) ? $data : $dataTypeContent;
                    $model = app($options->model);
                    $query = $model::where($options->key,$relationshipData->{$options->column})->first();
                    $relatedDataType = Voyager::model('DataType')->where('model_name',$options->model)->first();
                @endphp

                @if(isset($query))
                    <p><a class="" href="{{ route('voyager.'.Str::slug($relatedDataType?$relatedDataType->slug:$query->getTable()).'.edit',$query->getKey()) }}" target="_blank">{{ $query->{$options->label} }}</a></p>
                @else
                    <p>{{ __('voyager::generic.no_results') }}</p>
                @endif

            @else
                @php
                    $model = app($options->model);
                    $query = $model::where($options->key,$dataTypeContent->{$options->column})->first();
                    $relatedDataType = Voyager::model('DataType')->where('model_name',$options->model)->first();
                @endphp
                @if(isset($options->readonly)) 
                    @php
                        $value =  $query?($query->getKey()??null):null;
                        $label =  $query->{$options->label}??null;
                    @endphp
                    @if($value)
                        <a href="{{ route('voyager.'.Str::slug($relatedDataType?$relatedDataType->slug:$query->getTable()).'.edit',$query->getKey()) }}" target="_blank">@lang('voyager::generic.browse')</a>
                    @endif
                    <input   
                        type="hidden" name="{{ $options->column }}"  value="{{ $value }}">
                    <input  readonly 
                        type="text" class="form-control"  value="{{ $label }}">
                @else
                    @php
                        $query = $model::where($options->key, old($options->column, $dataTypeContent->{$options->column}))->get();
                    @endphp
                    <select
                        class="form-control select2-ajax" name="{{ $options->column }}"
                        data-get-items-route="{{route('voyager.' . $dataType->slug.'.relation')}}"
                        data-get-items-field="{{$row->field}}"
                        @if(!is_null($dataTypeContent->getKey())) data-id="{{$dataTypeContent->getKey()}}" @endif
                        data-method="{{ !is_null($dataTypeContent->getKey()) ? 'edit' : 'add' }}"
                    >
                        @if(!$row->required)
                            <option value="">{{__('voyager::generic.none')}}</option>
                        @endif

                        @foreach($query as $relationshipData)
                            <option value="{{ $relationshipData->{$options->key} }}" @if(old($options->column, $dataTypeContent->{$options->column}) == $relationshipData->{$options->key}) selected="selected" @endif>{{ $relationshipData->{$options->label} }}</option>
                        @endforeach
                    </select>
                @endif

            @endif

        @elseif($options->type == 'hasOne')

            @php
                $relationshipData = (isset($data)) ? $data : $dataTypeContent;

                $model = app($options->model);
                $query = $model::where($options->column, '=', $relationshipData->{$options->key})->first();

            @endphp

            @if(isset($query))
                <p>{{ $query->{$options->label} }}</p>
            @else
                <p>{{ __('voyager::generic.no_results') }}</p>
            @endif

        @elseif($options->type == 'hasMany')

            @if(isset($view) && ($view == 'browse' || $view == 'read'))

                @php
                    $relationshipData = (isset($data)) ? $data : $dataTypeContent;
                    $model = app($options->model);

                    $selected_values = $model::where($options->column, '=', $relationshipData->{$options->key})->get()->map(function ($item, $key) use ($options) {
                        return $item->{$options->label};
                    })->all();
                @endphp

                @if($view == 'browse')
                    @php
                        $string_values = implode(", ", $selected_values);
                        if(mb_strlen($string_values) > 25){ $string_values = mb_substr($string_values, 0, 25) . '...'; }
                    @endphp
                    @if(empty($selected_values))
                        <p>{{ __('voyager::generic.no_results') }}</p>
                    @else
                        <p>{{ $string_values }}</p>
                    @endif
                @else
                    @php
                        $model = app($options->model);
                        $query = $model::where($options->column, '=', $dataTypeContent->{$options->key})->get();
                    @endphp

                    @if(isset($query) && $query->count())
                        <ul>
                            @foreach($query as $query_res)
                                <li><a href="{{ route('voyager.'.Str::slug($options->table).'.show',['id'=>$query_res->{$options->key}]) }}" target="_blank"> {{ $query_res->{$options->label} }}</a></li>
                            @endforeach
                        </ul>

                    @else
                        <p>{{ __('voyager::generic.no_results') }}</p>
                    @endif
                @endif

            @else

                @php
                    $model = app($options->model);
                    $query = $model::where($options->column, '=', $dataTypeContent->{$options->key})->get();
                @endphp

                @if(isset($query) && $query->count())
                    <ul>
                        @foreach($query as $query_res)
                            <li><a href="{{ route('voyager.'.Str::slug($options->table).'.show',['id'=>$query_res->{$options->key}]) }}" target="_blank"> {{ $query_res->{$options->label} }}</a></li>
                        @endforeach
                    </ul>
                @else
                    <p>{{ __('voyager::generic.no_results') }}</p>
                @endif

            @endif

        @elseif($options->type == 'belongsToMany')

            @if(isset($view) && ($view == 'browse' || $view == 'read'))

                @php
                    $relationshipData = (isset($data)) ? $data : $dataTypeContent;

                    $selected_values = isset($relationshipData) ? $relationshipData->belongsToMany($options->model, $options->pivot_table, $options->foreign_pivot_key ?? null, $options->related_pivot_key ?? null, $options->parent_key ?? null, $options->key)->get()->map(function ($item, $key) use ($options) {
            			return $item->{$options->label};
            		})->all() : array();
                @endphp

                @if($view == 'browse')
                    @php
                        $string_values = implode(", ", $selected_values);
                        if(mb_strlen($string_values) > 25){ $string_values = mb_substr($string_values, 0, 25) . '...'; }
                    @endphp
                    @if(empty($selected_values))
                        <p>{{ __('voyager::generic.no_results') }}</p>
                    @else
                        <p>{{ $string_values }}</p>
                    @endif
                @else
                    @if(empty($selected_values))
                        <p>{{ __('voyager::generic.no_results') }}</p>
                    @else
                        <ul>
                            @foreach($selected_values as $selected_value)
                                <li>{{ $selected_value }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endif

            @else
                @php
                    $selected_values = isset($dataTypeContent) ? $dataTypeContent->belongsToMany($options->model, $options->pivot_table, $options->foreign_pivot_key ?? null, $options->related_pivot_key ?? null, $options->parent_key ?? null, $options->key)->get()->map(function ($item, $key) use ($options) {
                        return $item->{$options->key};
                    })->all() : array();
                    $relationshipOptions = app($options->model)->all();
                    $selected_values = old($relationshipField, $selected_values);
                    $relationshipOptions = $relationshipOptions->filter(function ($option, $key) use ($options,$selected_values) {
                        return in_array($option->{$options->key}, $selected_values);
                    });
                @endphp
                @if(isset($options->readonly)) 
                    @if ($relationshipOptions->count())
                    <ul style="padding-left: 0">
                        @foreach ($relationshipOptions as $relationshipOption)
                            <li class="form-control" style="margin-bottom: 5px">
                                <input type="hidden" name="{{ $relationshipField }}[]"  value="{!! printInt($relationshipOption->{$options->key}) !!}">
                                <a href="{{ route('voyager.'.Str::slug($options->table).'.show',['id'=>$relationshipOption->{$options->key}]) }}" target="_blank"> {{ $relationshipOption->{$options->label} }}</a>
                            </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="form-control">{{ __('voyager::generic.no_results') }}</p>
                    @endif
                @else
                <select
                    class="form-control @if(isset($options->taggable) && $options->taggable === 'on') select2-taggable @else select2-ajax @endif"
                    name="{{ $relationshipField }}[]" multiple
                    data-get-items-route="{{route('voyager.' . $dataType->slug.'.relation')}}"
                    data-get-items-field="{{$row->field}}"
                    @if(!is_null($dataTypeContent->getKey())) data-id="{{$dataTypeContent->getKey()}}" @endif
                    data-method="{{ !is_null($dataTypeContent->getKey()) ? 'edit' : 'add' }}"
                    @if(isset($options->taggable) && $options->taggable === 'on')
                        data-route="{{ route('voyager.'.\Illuminate\Support\Str::slug($options->table).'.store') }}"
                        data-label="{{$options->label}}"
                        data-error-message="{{__('voyager::bread.error_tagging')}}"
                    @endif
                >

                       

                        @if(!$row->required)
                            <option value="">{{__('voyager::generic.none')}}</option>
                        @endif

                        @foreach($relationshipOptions as $relationshipOption)
                            <option value="{{ $relationshipOption->{$options->key} }}" @if(in_array($relationshipOption->{$options->key}, $selected_values)) selected="selected" @endif>{{ $relationshipOption->{$options->label} }}</option>
                        @endforeach

                </select>
                @endif
            @endif
        @endif
    @else

        cannot make relationship because {{ $options->model }} does not exist.

    @endif

@endif
