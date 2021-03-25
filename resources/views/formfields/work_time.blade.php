@if($row->field  == "monday_work_time")
<div class="row">
    <div class="col-md-3">
        <input id="group_edit" type="checkbox" class="toggleswitch" data-width="250" value="off" data-on="Групповое редактирование" data-off="Обычное редактирование">
    </div>
</div>
@endif


<input id="{{ $row->field }}" type="hidden" class="form-control work_time" name="{{ $row->field }}"
    data-name="{{ $row->display_name }}"
    @if($row->required == 1) required @endif 
    value="@if(isset($dataTypeContent->{$row->field})){{ old($row->field, $dataTypeContent->{$row->field}) }}@else{{old($row->field)}}@endif">

@php
$weekday = str_replace("_work_time","",$row->field);

if(isset($dataTypeContent->{$row->field})){
$weekdayField = old($row->field, $dataTypeContent->{$row->field});
}else{
$weekdayField = old($row->field);
}

$weekdayField = json_decode($weekdayField);
// dd($weekdayField );
//В случае если поле пустое
$weekdayField = isset($weekdayField)? $weekdayField : [ 0=>'',1=>'' ];
// dd($weekday);
@endphp

<div class="row">
    <div class="col-md-3">
        <input id="{{$weekday}}-open" class="form-control open-hour timepicker" readonly
            {{$weekdayField[0] == 'on' || $weekdayField[0] == 'off' ? 'disabled' : ''}} type="text" placeholder="С"
            name="{{$weekday}}-open"
            {{$weekdayField[0] == 'on' || $weekdayField[0] == 'off' ? '' : 'value='.$weekdayField[0]}}>
    </div>
    <div class="col-md-3">
        <input id="{{$weekday}}-close" class="form-control close-hour timepicker" readonly
            {{$weekdayField[0] == 'on' || $weekdayField[0] == 'off' ? 'disabled' : ''}} type="text" placeholder="По"
            name="{{$weekday}}-close"
            {{$weekdayField[0] == 'on' || $weekdayField[0] == 'off' ? '' : 'value='.$weekdayField[1]}}>
    </div>
    <div class="col-md-6">
        <input id="{{$weekday}}-non-stop" type="checkbox" name="{{$weekday}}-non-stop" class="non-stop-check toggleswitch" data-width="200"
            value="on" @if($weekdayField[0]=='on' )checked="checked" @else @if($weekdayField[0]=='off' ) disabled @endif
            @endif data-on="Круглосуточно" data-off="По расписанию">

        <input id="{{$weekday}}-stop" type="checkbox" name="{{$weekday}}-stop" class="stop-check toggleswitch" data-width="200" value="off"
            @if($weekdayField[0]=='off' )checked="checked" @else @if($weekdayField[0]=='on' ) disabled @endif @endif
            data-on="Выходной" data-off="Рабочий день">
    </div>
</div>
