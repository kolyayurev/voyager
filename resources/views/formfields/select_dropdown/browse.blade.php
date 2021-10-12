@if (property_exists($row->details, 'options'))
{!! __($row->details->options->{$dataTypeContent->{$row->field}} ?? '') !!}
@endif
