@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
</div>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <!-- Adding / Editing -->
        @php
            $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
            $main_rows = ['title','slug','excerpt'];
            $top_side_rows = ['visible'];
            $images_rows = ['image'];
            $text_rows = ['body'];
            $seo_rows = ['meta_title','meta_description','h1'];
            $exclude_rows = array_merge($main_rows,$top_side_rows,$images_rows,$text_rows,$seo_rows);
            $others_rows = $dataTypeRows->whereNotIn('field',$exclude_rows)->pluck('field')->toArray();
        @endphp
        <!-- form start -->
        <form role="form"
                class="form-edit-add"
                action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                method="POST" enctype="multipart/form-data">
                <!-- PUT Method if we are editing -->
                @if($edit)
                    {{ method_field("PUT") }}
                @endif
                <!-- CSRF TOKEN -->
                {{ csrf_field() }}
                {{-- ERRORS --}}
                @if (count($errors) > 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-body">
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- END ERRORS --}}
    
                <div class="row">
                    @include('voyager::bread.partials.edit-panel',['col'=>8,'header'=> false, 'fields'=>$main_rows])
                    @include('voyager::bread.partials.edit-panel',['col'=>4,'header'=> false, 'fields'=>$top_side_rows])
                </div>
                {{-- IMAGES --}}
                <div class="row">
                    @include('voyager::bread.partials.edit-panel',['title'=> __('voyager::fields.field_groups.images'), 'fields'=>$images_rows])
                </div>
                {{-- END IMAGES --}}

                {{-- DESCRIPTION --}}
                <div class="row">
                    @include('voyager::bread.partials.edit-panel',['title'=> __('Описание'),'type'=>'fullscreen', 'fields'=>$text_rows])
                </div>
                {{-- SEO --}}
                <div class="row">
                    @include('voyager::bread.partials.edit-panel',[  'title'=> __('voyager::fields.field_groups.seo'), 
                                                                'fields'=>$seo_rows,
                    ])
                </div>
                {{-- END SEO --}}
                {{-- OTHERS --}}
                @if (count($others_rows))
                <div class="row">
                    @include('voyager::bread.partials.edit-panel',[  'title'=> __('voyager::fields.field_groups.others'), 
                                                                'fields'=>$others_rows,
                                                                ])
                </div>
                @endif
                {{-- END OTHERS --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                @section('submit-buttons')
                                    <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                                @stop
                                @yield('submit-buttons')
                            </div>
                        </div>
                    </div>
                </div>
        </form>

        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
            <input name="image" id="upload_file" type="file"
                        onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
            {{ csrf_field() }}
        </form>

        @if ($edit)
            @include('voyager::bread.partials.widgets')
        @endif
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

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

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();

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

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
@stop
