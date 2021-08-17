@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.__('voyager::generic.bread'))

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-bread"></i> {{ __('voyager::generic.bread') }}
    </h1>
@stop

@section('content')

    <div class="page-content container-fluid">
        @include('voyager::alerts')
        
        <div class="row">
            <div class="col-md-12">

                <table class="table table-striped database-tables">
                    <thead>
                        <tr>
                            <th>{{ __('voyager::database.table_name') }}</th>
                            <th style="text-align:right">{{ __('voyager::bread.bread_crud_actions') }}</th>
                        </tr>
                    </thead>

                @foreach($tables as $table)
                    @continue(in_array($table->name, config('voyager.database.tables.hidden', [])))
                    <tr>
                        <td>
                            <p class="name">
                                <a href="{{ route('voyager.database.show', $table->prefix.$table->name) }}"
                                   data-name="{{ $table->prefix.$table->name }}" class="desctable">
                                   {{ $table->name }}
                                </a>
                                <i class="voyager-data"
                                   style="font-size:25px; position:absolute; margin-left:10px; margin-top:-3px;"></i>
                            </p>
                        </td>

                        <td class="actions text-right">
                            @if($table->dataTypeId)
                                <a href="{{ route('voyager.' . $table->slug . '.index') }}"
                                   class="btn btn-warning btn-sm browse_bread" style="margin-right: 0;">
                                    <i class="voyager-plus"></i> {{ __('voyager::generic.browse') }}
                                </a>
                                <a href="{{ route('voyager.bread.edit', $table->slug) }}"
                                   class="btn btn-primary btn-sm edit">
                                    <i class="voyager-edit"></i> {{ __('voyager::generic.edit') }}
                                </a>
                                <a href="{{ route('voyager.bread.create_seeder', $table->slug) }}"
                                    class="btn btn-warning btn-sm edit">
                                     <i class="voyager-external"></i> {{ __('voyager::generic.export') }}
                                 </a>
                                <a href="#delete-bread" data-id="{{ $table->dataTypeId }}" data-slug="{{ $table->slug }}"
                                     class="btn btn-danger btn-sm delete">
                                    <i class="voyager-trash"></i> {{ __('voyager::generic.delete') }}
                                </a>
                            @else
                                <a href="{{ route('voyager.bread.create', $table->name) }}"
                                   class="_btn btn-default btn-sm pull-right">
                                    <i class="voyager-plus"></i> {{ __('voyager::bread.add_bread') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <button class="btn btn-primary btn-sm" id="createCustomBread">
                     <i class="voyager-plus"></i> {{ __('voyager::bread.create_custom_bread') }}
                </button>
            </div>
            <div class="col-md-12">
                <table class="table table-striped database-tables">
                    <thead>
                        <tr>
                            <th>{{ __('voyager::bread.custom_bread') }}</th>
                            <th style="text-align:right">{{ __('voyager::bread.bread_crud_actions') }}</th>
                        </tr>
                    </thead>
                    @foreach($customDataTypes as $customDataType)
                        <tr>
                            <td>
                                <p class="name">
                                    {{ $customDataType->slug }}
                                </p>
                            </td>

                            <td class="actions text-right">
                                <a href="{{ route('voyager.' . $customDataType->slug . '.index') }}"
                                class="btn btn-warning btn-sm browse_bread" style="margin-right: 0;">
                                    <i class="voyager-plus"></i> {{ __('voyager::generic.browse') }}
                                </a>
                                <a href="{{ route('voyager.bread.edit', $customDataType->slug) }}"
                                class="btn btn-primary btn-sm edit">
                                    <i class="voyager-edit"></i> {{ __('voyager::generic.edit') }}
                                </a>
                                <a href="{{ route('voyager.bread.create_seeder', $customDataType->slug) }}"
                                    class="btn btn-warning btn-sm edit">
                                    <i class="voyager-external"></i> {{ __('voyager::generic.export') }}
                                </a>
                                <a href="#delete-bread" data-id="{{ $customDataType->id }}" data-slug="{{ $customDataType->slug }}"
                                    class="btn btn-danger btn-sm delete">
                                    <i class="voyager-trash"></i> {{ __('voyager::generic.delete') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    {{-- Delete BREAD Modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_builder_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i>  {!! __('voyager::bread.delete_bread_quest', ['table' => '<span id="delete_builder_slug"></span>']) !!}</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_builder_form" method="POST">
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ __('voyager::bread.delete_bread_conf') }}">
                    </form>
                    <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="vueModals">
        <div class="modal modal-info fade" tabindex="-1" id="table_info" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-data"></i> @{{ table.name }}</h4>
                    </div>
                    <div class="modal-body" style="overflow:scroll">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>{{ __('voyager::database.field') }}</th>
                                <th>{{ __('voyager::database.type') }}</th>
                                <th>{{ __('voyager::database.null') }}</th>
                                <th>{{ __('voyager::database.key') }}</th>
                                <th>{{ __('voyager::database.default') }}</th>
                                <th>{{ __('voyager::database.extra') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="row in table.rows">
                                <td><strong>@{{ row.Field }}</strong></td>
                                <td>@{{ row.Type }}</td>
                                <td>@{{ row.Null }}</td>
                                <td>@{{ row.Key }}</td>
                                <td>@{{ row.Default }}</td>
                                <td>@{{ row.Extra }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ __('voyager::generic.close') }}</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    
        <v-dialog-create-custom-bread
            ref="dialogCreateCustomBread"
            title="{{ __('voyager::bread.create_custom_bread') }}"
            url="{{ route('voyager.bread.create_custom') }}"
            >
            <div slot="field">
                @csrf
            </div>
        </v-dialog-create-custom-bread>
    </div>

@stop

@section('javascript')

    <script>

        var table = {
            name: '',
            rows: []
        };

        var vueModals = new Vue({
            el: '#vueModals',
            data() {
                return {
                    table: table,
                }
            },
        });

        $(function () {

            // Create custom BREAD
            //
            $('#createCustomBread').on('click',  function (e) {
                vueModals.$refs.dialogCreateCustomBread.openDialog();
            });
            // Setup Delete BREAD
            //
            $('table .actions').on('click', '.delete', function (e) {
                id = $(this).data('id');
                slug = $(this).data('slug');

                $('#delete_builder_slug').text(slug);
                $('#delete_builder_form')[0].action = '{{ route('voyager.bread.delete', ['__id']) }}'.replace('__id', id);
                $('#delete_builder_modal').modal('show');
            });

            // Setup Show Table Info
            //
            $('.database-tables').on('click', '.desctable', function (e) {
                e.preventDefault();
                href = $(this).attr('href');
                table.name = $(this).data('name');
                table.rows = [];
                $.get(href, function (data) {
                    $.each(data, function (key, val) {
                        table.rows.push({
                            Field: val.field,
                            Type: val.type,
                            Null: val.null,
                            Key: val.key,
                            Default: val.default,
                            Extra: val.extra
                        });
                        $('#table_info').modal('show');
                    });
                });
            });
        });
    </script>

@stop
