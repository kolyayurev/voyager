@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')


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
            $images_rows = ['image','photos'];
            $seo_rows = ['meta_title','meta_description','meta_keywords','h1'];
            $exclude_rows = array_merge($main_rows,$top_side_rows,$images_rows,$seo_rows);
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
                @csrf
                @include('voyager::bread.partials.edit-alerts')

                {{-- END ERRORS --}}
                <div class="row">
                    @include('voyager::bread.partials.edit-panel',['col'=>8,'header'=> false, 'fields'=>$main_rows])
                    @include('voyager::bread.partials.edit-panel',['col'=>4,'header'=> false, 'fields'=>$top_side_rows])
                {{-- MEDIA --}}
                    @include('voyager::bread.partials.edit-panel',['title'=> __('voyager::fields.field_groups.media'), 'fields'=>$images_rows])
                {{-- END IMAGES --}}

                {{-- SEO --}}
                    @include('voyager::bread.partials.edit-panel',[  'title'=> __('voyager::fields.field_groups.seo'), 
                                                                'fields'=>$seo_rows,
                    ])
                {{-- END SEO --}}
                {{-- OTHERS --}}
                @if (count($others_rows))
                    @include('voyager::bread.partials.edit-panel',[  'title'=> __('voyager::fields.field_groups.others'), 
                                                                'fields'=>$others_rows,
                                                                ])
                @endif
                </div>
                {{-- END OTHERS --}}
               
                <div class="row">
                    <x-voyager::panel  :header="false" >
                        @section('submit-buttons')
                            <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                        @stop
                        @yield('submit-buttons')
                        @foreach($actions as $action)
                            @if (!method_exists($action, 'massAction'))
                                @include('voyager::bread.partials.edit-add.actions', ['action' => $action])
                            @endif
                        @endforeach
                    </x-voyager::panel>
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
    @include('voyager::bread.partials.modals.delete-file')
    @include('voyager::bread.partials.edit-common-js')

@stop

