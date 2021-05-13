@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .voyager .panel{
            margin-bottom: 0;
        }
        #list_indications .list__items{
            max-height: 250px;
            overflow-y: auto;
        }
    </style>
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <!-- Adding / Editing -->
        @php
            $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
            $main_rows = ['name','slug','description','value'];
            $top_side_rows = ['handler','table_name','foreign_key'];
            $editor_rows   = ['details'];
            $exclude_rows = array_merge($main_rows,$top_side_rows, $editor_rows );
            $count_others = $dataTypeRows->whereNotIn('field',$exclude_rows)->count();
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
                    <div class="col-md-8">
                        <div class="panel">
                            <div class="panel-body">
                                @foreach($dataTypeRows as $row)
                                    @if (in_array($row->field, $main_rows))
                                        @include('voyager::bread.partials.edit-row')
                                    @endif
                                @endforeach
                            </div>
                        </div><!-- .panel -->
                    </div>
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-body" id="sideRows" v-cloak>
                                @foreach($dataTypeRows as $row)
                                    @switch($row->field)
                                        @case('handler')
                                            <div class="form-group col-md-12 {{ $errors->has($row->field) ? 'has-error' : '' }}" >
                                                <label class="control-label" for="handler">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                                <input type="hidden"  v-model="handler" name="handler">
                
                                                <el-select style="width: 100%" v-model="handler" >
                                                    @foreach(Voyager::widgetHandlers() as $widget)
                                                    <el-option
                                                      label="{{$widget->getName()}}"
                                                      value="{{$widget->getCodename()}}">
                                                    </el-option>
                                                    @endforeach
                                                </el-select>
                
                                                @if ($errors->has($row->field))
                                                    @foreach ($errors->get($row->field) as $error)
                                                        <span class="help-block">{{ $error }}</span>
                                                    @endforeach
                                                @endif
                                            </div>
                                            @break
                                        @case('table_name')
                                            <div class="form-group col-md-12 {{ $errors->has($row->field) ? 'has-error' : '' }}" >
                                                <label class="control-label" for="table_name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                                <input type="hidden"  v-model="table_name" name="table_name">
                
                                                <el-select style="width: 100%" v-model="table_name" clearable @change="handleDataTypeChange">
                                                    <el-option
                                                      v-for="type in dataTypes"
                                                      :key="type.id"
                                                      :label="type.display_name_plural"
                                                      :value="type.name">
                                                    </el-option>
                                                </el-select>

                                                @if ($errors->has($row->field))
                                                    @foreach ($errors->get($row->field) as $error)
                                                        <span class="help-block">{{ $error }}</span>
                                                    @endforeach
                                                @endif
                                            </div>
                                            @break
                                        @case('foreign_key')
                                            <div class="form-group col-md-12 {{ $errors->has($row->field) ? 'has-error' : '' }}" >
                                                <label class="control-label" for="foreign_key">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                                <input type="hidden"  v-model="foreign_key" name="foreign_key">
                                                <el-select
                                                        style="width: 100%"
                                                        v-model="foreign_key"
                                                        filterable
                                                        remote
                                                        clearable
                                                        reserve-keyword
                                                        placeholder="@lang('voyager::fields.hints.start_typing')"
                                                        :remote-method="getDataTypeContent"
                                                        :loading="prLoading">
                                                    <el-option
                                                        v-for="item in dataTypeContent"
                                                        :key="item.id"
                                                        :label="item[getDataTypeSearchField()]"
                                                        :value="item.id">
                                                    </el-option>
                                                </el-select>
                                                @if ($errors->has($row->field))
                                                    @foreach ($errors->get($row->field) as $error)
                                                        <span class="help-block">{{ $error }}</span>
                                                    @endforeach
                                                @endif
                                            </div>
                                            @break    
                                    @endswitch
                                @endforeach
                            </div>
                        </div><!-- .panel -->
                    </div>
                    
                </div>
                {{-- EDITOR --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">{{ __('voyager::fields.editor') }}</h3>
                                <div class="panel-actions">
                                    <a class="panel-action panel-collapsed voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                                </div>
                            </div>
                            <div class="panel-body" style="display:none;">
                                @foreach($dataTypeRows as $row)
                                    @if (in_array($row->field, $editor_rows))
                                        @include('voyager::bread.partials.edit-row')
                                    @endif
                                @endforeach
                            </div>
                        </div><!-- .panel -->
                    </div>
                </div>

                {{-- OTHERS --}}
                @if ($count_others)
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="icon wb-clipboard"></i> {{ __('voyager::fields.field_groups.others') }}</h3>
                                <div class="panel-actions">
                                    <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                                </div>
                            </div>
                            <div class="panel-body">
                                @foreach($dataTypeRows as $row)
                                    @if (!in_array($row->field,$exclude_rows))
                                        @include('voyager::bread.partials.edit-row')
                                    @endif
                                @endforeach
                            </div>
                        </div><!-- .panel -->
                    </div>
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
@php
// ->select('id','name','slug','details')
    $dataTypes = Voyager::model('DataType')->widgetable()->get();
@endphp

@push('vue')
<script>
    new Vue({
        el: "#sideRows",
        data(){
            return {
                dataTypes: {!! printArray($dataTypes) !!},
                dataTypeContent: [],
                handler: {!!  printString($dataTypeContent->handler) !!},
                table_name: {!!  printString($dataTypeContent->table_name) !!},
                foreign_key: {!!  printString($dataTypeContent->foreign_key) !!},
            }
        },
        mounted(){
            this.getDataTypeContent('')
        },
        methods:{
            getDataTypeContent(query){
                var _this = this
                let data = new FormData();
                data.append('data_type',_this.table_name);
                data.append('search',query);

                let url = '{{ route('voyager.widgets.get_data_type_content_items') }}';

                _this.baseAxios(url, data, function (response) {
                    _this.dataTypeContent = response.data.results;
                },
                function (response) {
                    _this.warningMsg(response.data.message);
                });
            },
            handleDataTypeChange(){
                this.getDataTypeContent('')
            },
            getDataTypeSearchField(){
                return this.dataTypes.find(item => item.name === this.table_name ).details.default_widget_search_key;
            }
        }
    });
</script>
@endpush

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
                $('.side-body').multilingual({"editing": true,"vueInstances":vueFieldInstances});
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
        });
    </script>
@stop
