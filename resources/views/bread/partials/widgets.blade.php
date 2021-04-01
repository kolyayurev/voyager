@if ($dataType->isWidgetable() && model_has_widgets($dataType->model_name) )

@php
    $widgets = $dataTypeContent->widgets;
@endphp
    @if ($widgets->count())
    @php
        $widgetDataType =  Voyager::model('DataType')->where('slug', 'widgets')->first();
    @endphp
    @foreach ($widgets as $widget)
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $widget->getTranslatedAttribute('name') }}</h3>
                    <div class="panel-actions">
                        <a class="panel-action panel-collapsed voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body" style="display:none;">
                    {!! app('voyager')->widgetDisplay($widgetDataType, $widget) !!}  
                </div>
            </div><!-- .panel -->
        </div>
    </div>
    @endforeach

    @endif
@endif

