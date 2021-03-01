<textarea @if($row->required == 1) required @endif 
    @if(isset($options->disabled)) disabled @endif
    @if(isset($options->readonly)) readonly @endif
    class="form-control" name="{{ $row->field }}" rows="{{ $options->display->rows ?? 5 }}">{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}</textarea>
