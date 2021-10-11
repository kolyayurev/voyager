<br>
<?php $checked = false; ?>
@if(isset($dataTypeContent->{$row->field}) || old($row->field))
    <?php $checked = old($row->field, $dataTypeContent->{$row->field}); ?>
@else
    <?php $checked = isset($options->checked) &&
        filter_var($options->checked, FILTER_VALIDATE_BOOLEAN) ? true: false; ?>
@endif

<?php $class = $options->class ?? "toggleswitch"; ?>

<input type="checkbox" name="{{ $row->field }}" class="{{ $class }}"
    @if(isset($options->disabled)) disabled @endif
    @if(isset($options->readonly)) readonly @endif
    data-on="{{ __(isset($options->on)?$options->on:'voyager::form.checkbox.on') }}" {!! $checked ? 'checked="checked"' : '' !!}
    data-off="{{ __(isset($options->off)?$options->off:'voyager::form.checkbox.off') }}">

