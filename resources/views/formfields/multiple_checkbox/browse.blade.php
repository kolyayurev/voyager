@if (property_exists($row->details, 'options'))
    @if (@count(json_decode($dataTypeContent->{$row->field})) > 0)
        @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
            @if (@$row->details->options->{$item})
                {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
            @endif
        @endforeach
    @else
        {{ __('voyager::generic.none') }}
    @endif
@endif
