<br>
<?php $checked = false; ?>
@if(isset($options->options))
    @php
        $checkedData = old($row->field, $dataTypeContent->{$row->field});
        $checkedData = is_array($checkedData) ? $checkedData : json_decode($checkedData, true);
    @endphp
    @foreach($options->options as $key => $label)
        @if(isset($dataTypeContent->{$row->field}) || old($row->field))
            @php
                $checked = in_array($key, $checkedData);
            @endphp
        @else
            @php
                $checked = isset($options->checked) && $options->checked ? true : false;
            @endphp
        @endif

        <input type="checkbox" name="{{ $row->field }}[{{$key}}]" {!! $checked ? 'checked="checked"' : '' !!} value="{{$key}}" id="{{$key}}"/>
        <label for="{{$key}}">{{$label}}</label>
    @endforeach
@endif
