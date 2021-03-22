@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title',$dataTypeContent->getTranslatedAttribute('name'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ $dataType->getTranslatedAttribute('display_name_singular').' "'.$dataTypeContent->getTranslatedAttribute('name').'"' }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop



@section('content')
    <div class="container-fluid">
        @include('voyager::alerts')
        @if(config('voyager.show_dev_tips'))
        <div class="alert alert-info">
            <strong>{{ __('voyager::generic.how_to_use') }}:</strong>
            <p>{{ __('voyager::settings.usage_help') }} <code>widget('{{$dataTypeContent->slug}}')</code></p>
        </div>
        @endif
    </div>
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <form role="form"
                            id="form"
                            class="form-edit-add"
                            action="{{ route('voyager.'.$dataType->slug.'.moderate_update', $dataTypeContent->getKey()) }}"
                            method="POST" enctype="multipart/form-data">

                        @method("PUT")
                        @csrf

                        <div class="panel-body">
                            @yield('form')
                        </div>
                       
                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        $('document').ready(function () {
            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true,"vueInstances":vueFieldInstances});
            @endif    
        });
    </script>
@endpush