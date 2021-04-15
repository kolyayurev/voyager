
@php
    $col          =  $col??12;
    $titles       =  $titles??[];
    $field_groups = $field_groups??[];
@endphp
@if (count($titles) && count($field_groups))
<div class="col-md-{{$col}}">
    <ul class="nav nav-tabs ">
        @foreach ($titles as $title)
            @php
                $visible  =  count($dataTypeRows) &&  array_key_exists($loop->index,$field_groups) && $dataTypeRows->whereIn('field',$field_groups[$loop->index])->count();
            @endphp
            @if ($visible)
                <li @if ($loop->first) class="active" @endif>
                    <a data-toggle="tab" href="#tab_{{$loop->index}}">{{$title}}</a>
                </li>
            @endif
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach ($field_groups as $field_group)
            @php
                $visible  =  count($dataTypeRows) && $dataTypeRows->whereIn('field',$field_group)->count();
            @endphp
            @if ($visible)
                <div id="tab_{{$loop->index}}" class="tab-pane fade in @if ($loop->first) active @endif ">
                    @foreach($dataTypeRows as $row)
                        @if (in_array($row->field, $field_group))
                            @include('voyager::bread.partials.edit-row')
                        @endif
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
</div>
@endif
