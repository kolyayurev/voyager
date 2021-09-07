<input 
       @if($row->required == 1) required @endif 
       @if(isset($options->readonly)) readonly @endif
       type="datetime" class="form-control datepicker" 
       name="{{ $row->field }}"
       value="@if(isset($dataTypeContent->{$row->field})){{ \Carbon\Carbon::parse(old($row->field, $dataTypeContent->{$row->field}))->format('m/d/Y H:i') }}@else{{old($row->field)}}@endif">
