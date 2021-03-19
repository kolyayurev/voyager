@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title',$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.edit').' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <form role="form"
                            class="form-edit-add"
                            action="{{ route('voyager.'.$dataType->slug.'.builder_store', $dataTypeContent->getKey()) }}"
                            method="POST" enctype="multipart/form-data">

                        @method("PUT")
                        @csrf

                        <div class="panel-body">
                            @dump($dataTypeContent)

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