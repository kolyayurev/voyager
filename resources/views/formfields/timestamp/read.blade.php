@if ( property_exists($row->details, 'format') && !is_null($dataTypeContent->{$row->field}) )
    {{ \Carbon\Carbon::parse($dataTypeContent->{$row->field})->formatLocalized($row->details->format) }}
@else
    {{ $dataTypeContent->{$row->field} }}
@endif