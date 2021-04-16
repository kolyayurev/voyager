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
@push('javascript')
<script>
    $(document).ready(function() {
        jQuery(function ($) {
                $.timepicker.regional['ru'] = {
                    hourText: 'Часы',
                    minuteText: 'Минуты',
                    amPmText: ['AM', 'PM'],
                    closeButtonText: 'Готово',
                    nowButtonText: 'Сейчас',
                    deselectButtonText: 'Снять выделение'
                }
                $.timepicker.setDefaults($.timepicker.regional['ru']);

                $('#group_edit').change(function () {
                    var stopCheck = $('.stop-check');
                    var nonStopCheck = $('.non-stop-check');
                    if ($("#group_edit").prop('checked')) {
                        nonStopCheck.bootstrapToggle('enable');
                        stopCheck.bootstrapToggle('enable');
                        nonStopCheck.bootstrapToggle('off');
                        stopCheck.bootstrapToggle('off');
                        nonStopCheck.bootstrapToggle('disable');
                        stopCheck.bootstrapToggle('disable');
                        $('.open-hour').prop('disabled', true);
                        $('.close-hour').prop('disabled', true);

                        $('#monday-open').prop('disabled', false);
                        $('#monday-close').prop('disabled', false);

                        $("#monday-stop").bootstrapToggle('enable');
                        $("#monday-non-stop").bootstrapToggle('enable');
                    } else {
                        $('.open-hour').prop('disabled', false);
                        $('.close-hour').prop('disabled', false);
                        nonStopCheck.bootstrapToggle('enable');
                        stopCheck.bootstrapToggle('enable');
                    }
                });

                if ($('.timepicker').length > 0) {
                    $('.timepicker').timepicker({ onSelect: onSelectCallback });
                }

                function onSelectCallback() {
                    var weekday = $(this).attr('id').replace("-close", "").replace("-open", "");
                    var arr = [$('#' + weekday + '-open').val(), $('#' + weekday + '-close').val()];
                    var myJSON = JSON.stringify(arr);
                    if (!$("#group_edit").prop('checked')) {
                        $('#' + weekday + '_work_time').val(myJSON);
                    } else {
                        if ($(this).attr('id').indexOf('open') > -1) {
                            $('.open-hour').val($('#' + weekday + '-open').val());
                        } else if ($(this).attr('id').indexOf('close') > -1) {
                            $('.close-hour').val($('#' + weekday + '-close').val());
                        }
                        $('.work_time').val(myJSON);
                    }
                }

                $('.non-stop-check').change(function () {
                    if (!$("#group_edit").prop('checked')) {
                        var openHour = $(this).parent().parent().parent().find('.open-hour');
                        var closeHour = $(this).parent().parent().parent().find('.close-hour');
                        var stopCheck = $(this).parent().parent().parent().find('.stop-check');
                        var weekday = $(this).attr('id').replace("-non-stop", "");
                        if ($(this).prop('checked')) {
                            openHour.prop('disabled', true);
                            closeHour.prop('disabled', true);
                            stopCheck.bootstrapToggle('disable');
                            stopCheck.bootstrapToggle('off');
                            openHour.val('');
                            closeHour.val('');
                            $('#' + weekday + '_work_time').val('["on"]');
                        } else {
                            stopCheck.bootstrapToggle('enable');
                            openHour.prop('disabled', false);
                            closeHour.prop('disabled', false);
                            $('#' + weekday + '_work_time').val('');
                        }
                    } else {
                        var openHour = $('.open-hour');
                        var closeHour = $('.close-hour');
                        if ($(this).prop('checked')) {
                            $('#monday-open').prop('disabled', true);
                            $('#monday-close').prop('disabled', true);
                            $("#monday-stop").bootstrapToggle('off');
                            $("#monday-stop").bootstrapToggle('disable');
                            openHour.val('');
                            closeHour.val('');
                            $('.work_time').val('["on"]');
                        } else {
                            $("#monday-stop").bootstrapToggle('enable');
                            $('#monday-open').prop('disabled', false);
                            $('#monday-close').prop('disabled', false);
                            $('.work_time').val('');
                        }
                    }
                });

                $('.stop-check').change(function () {
                    if (!$("#group_edit").prop('checked')) {
                        var openHour = $(this).parent().parent().parent().find('.open-hour');
                        var closeHour = $(this).parent().parent().parent().find('.close-hour');
                        var nonStopCheck = $(this).parent().parent().parent().find('.non-stop-check');
                        var weekday = $(this).attr('id').replace("-stop", "");

                        if ($(this).prop('checked')) {
                            openHour.prop('disabled', true);
                            closeHour.prop('disabled', true);
                            nonStopCheck.bootstrapToggle('disable');
                            nonStopCheck.bootstrapToggle('off');
                            openHour.val('');
                            closeHour.val('');
                            $('#' + weekday + '_work_time').val('["off"]');
                        } else {
                            nonStopCheck.bootstrapToggle('enable');
                            openHour.prop('disabled', false);
                            closeHour.prop('disabled', false);
                            $('#' + weekday + '_work_time').val('');
                        }
                    } else {
                        if ($(this).prop('checked')) {
                            $('#monday-open').prop('disabled', true);
                            $('#monday-close').prop('disabled', true);
                            $("#monday-non-stop").bootstrapToggle('disable');
                            $("#monday-non-stop").bootstrapToggle('off');
                            $('.open-hour').val('');
                            $('.close-hour').val('');
                            $('.work_time').val('["off"]');
                        } else {
                            $("#monday-non-stop").bootstrapToggle('enable');
                            $('#monday-open').prop('disabled', false);
                            $('#monday-close').prop('disabled', false);
                            $('.work_time').val('');
                        }
                    }
                });
            });

            
    });
</script>
@endpush

