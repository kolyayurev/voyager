@push('javascript')
<script>

    $('document').ready(function () {
        $('.toggleswitch').bootstrapToggle();

        //Init datepicker for date fields if data-datepicker attribute defined
        //or if browser does not handle date inputs
        $('.form-group input[type=date]').each(function (idx, elt) {
            if (elt.hasAttribute('data-datepicker')) {
                elt.type = 'text';
                $(elt).datetimepicker($(elt).data('datepicker'));
            } else if (elt.type != 'date') {
                elt.type = 'text';
                $(elt).datetimepicker({
                    format: 'L',
                    extraFormats: [ 'YYYY-MM-DD' ]
                }).datetimepicker($(elt).data('datepicker'));
            }
        });

        @if ($isModelTranslatable)
        var multilingual = $('.side-body').multilingual({"editing": true,"vueInstances":vueFieldInstances});
        @endif

        $('.side-body input[data-slug-origin]').each(function(i, el) {
            $(el).slugify();
        });

       
        $('[data-toggle="tooltip"]').tooltip();

        /********** WORK TIME **********/

        if($('#work_time_group_edit').length)
        {
            $.timepicker.regional['ru'] = {
                hourText: 'Часы',
                minuteText: 'Минуты',
                amPmText: ['AM', 'PM'],
                closeButtonText: 'Готово',
                nowButtonText: 'Сейчас',
                deselectButtonText: 'Снять выделение'
            }
            $.timepicker.setDefaults($.timepicker.regional['ru']);

            $('#work_time_group_edit').change(function () {
                var stopCheck = $('.stop-check');
                var nonStopCheck = $('.non-stop-check');
                if ($("#work_time_group_edit").prop('checked')) {
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
                if (!$("#work_time_group_edit").prop('checked')) {
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
                if (!$("#work_time_group_edit").prop('checked')) {
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
                if (!$("#work_time_group_edit").prop('checked')) {
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
        }
    /********** END WORK TIME **********/
        
    });

</script>
@endpush