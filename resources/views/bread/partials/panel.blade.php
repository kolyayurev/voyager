
@php
    $type   =  $type??"collapse";
    $fresh  =  $fresh??false;
    $header =  $header??true;
    $hidden =  $hidden??false;

@endphp

@if (count($dataTypeRows))
<div class="col-md-{{$col??12}}">
    <div class="panel  @if($fresh) fresh-color @endif panel-bordered panel-{{$color??'primary'}}">
        @if ($header)
        <div class="panel-heading">
            <h3 class="panel-title"><i class="icon wb-clipboard"></i> {{ $title??'' }}</h3>
            <div class="panel-actions">
                @switch($type)
                    @case('collapse')
                        <a class="panel-action  @if($hidden) panel-collapsed voyager-angle-down @else voyager-angle-up @endif" 
                            data-toggle="panel-collapse" 
                            aria-hidden="{{ $hidden }}"
                            >
                        </a>
                        @break
                    @case('fullscreen')
                        <a class="panel-action  voyager-resize-full" 
                            data-toggle="panel-fullscreen" 
                            aria-hidden="{{ $hidden }}"
                            >
                        </a>
                        @break
                @endswitch
            </div>
        </div>
        @endif

        <div class="panel-body" @if($hidden) style="display: none" @endif>
            @foreach($dataTypeRows as $row)
                @if (in_array($row->field, $fields))
                    @include('voyager::bread.partials.row')
                @endif
            @endforeach
        </div>
    </div><!-- .panel -->
</div>
@endif
