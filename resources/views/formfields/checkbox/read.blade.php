@if($dataTypeContent->{$row->field})
<span class="label label-info">{{ __(property_exists($row->details, 'on')? $row->details->on:'voyager::form.checkbox.on') }}</span>
@else
<span class="label label-primary">{{ __(property_exists($row->details, 'off')? $row->details->off:'voyager::form.checkbox.off') }}</span>
@endif