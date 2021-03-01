<input  @if($row->required == 1) required @endif 
        @if(isset($options->disabled)) disabled @endif
        @if(isset($options->readonly)) readonly @endif
        type="text" class="form-control" name="{{ $row->field }}"
        placeholder="{{ old($row->field, $options->placeholder ?? $row->getTranslatedAttribute('display_name')) }}"
       {!! isBreadSlugAutoGenerator($options) !!}
       value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">
