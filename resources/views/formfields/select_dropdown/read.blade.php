@if(property_exists($row->details, 'options') && !empty($row->details->options->{$dataTypeContent->{$row->field}}))
    @include('voyager::multilingual.input-hidden-bread-read')
    {!!  __($row->details->options->{$dataTypeContent->{$row->field}} ?? '') !!}
@endif
