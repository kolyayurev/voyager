@if (property_exists($row->details, 'options'))
{!! $row->details->options->{$dataTypeContent->{$row->field}} ?__($row->details->options->{$dataTypeContent->{$row->field}}): '' !!}
@endif
