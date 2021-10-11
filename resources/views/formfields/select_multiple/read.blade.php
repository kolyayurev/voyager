@if(property_exists($row->details, 'relationship'))

@foreach(json_decode($dataTypeContent->{$row->field}) as $item)
    {{ $item->{$row->field}  }}
@endforeach

@elseif(property_exists($row->details, 'options'))
@if (!empty(json_decode($dataTypeContent->{$row->field})))
    @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
        @if (@$row->details->options->{$item})
            {{ __($row->details->options->{$item}) . (!$loop->last ? ', ' : '') }}
        @endif
    @endforeach
@else
    {{ __('voyager::generic.none') }}
@endif
@endif