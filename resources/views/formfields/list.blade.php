@php
    $old_parameters = [];
    if($dataTypeContent->{$row->field}){
        if(!is_array($dataTypeContent->{$row->field}))
            $old_parameters = json_decode($dataTypeContent->{$row->field});
        else {
            $old_parameters = $dataTypeContent->{$row->field};
        }
    }
    $end_id = 0;
@endphp


<div class="list-formfield">
@if(!empty($old_parameters))

    @foreach($old_parameters as $parameter)
        <div class="form-group row" row-id="{{$loop->index}}">
            <div class="col-xs-6" style="margin-bottom:0;">
                <input type="text" class="form-control" name="{{ $row->field }}[{{$loop->index}}]" value="{{ $parameter }}" id="value"/>
            </div>
            
            <div class="col-xs-1" style="margin-bottom:0;">
                <button type="button" class="btn btn-xs btn-delete" style="margin-top:0px;" ><i class="voyager-trash"></i></button>
            </div>
        </div>
        @php 
            $end_id = $loop->index + 1;
        @endphp
    @endforeach
@endif
    <div class="form-group row" row-id="{{ $end_id }}">
        <div class="col-xs-6" style="margin-bottom:0;">
            <input type="text" class="form-control" 
            future-name="{{ $row->field }}[{{ $end_id }}]" 
            value="" id="value"/>
        </div>
        <div class="col-xs-1" style="margin-bottom:0;">
            <button type="button" class="btn btn-success btn-xs btn-add" style="margin-top:0px;"><i class="voyager-plus"></i></button>
        </div>
    </div>

    <input type="hidden" name="list" value="{{$row->field}}"/>
</div>



<script>

    function editNameCount(el){
        var str = el.getAttribute('future-name');
        var old_id = parseInt(el.parentNode.parentNode.getAttribute('row-id'));
        new_str = str.substring(0,str.indexOf('[')+1)
                    + (old_id+1)
                    + str.substring(str.indexOf(']'), str.length);
        return(new_str);
    }

    function addRow(){
        var that_row = this.parentNode.parentNode;
        var new_row = this.parentNode.parentNode.cloneNode(true);
        
        if(that_row.querySelector("#value").value == '')
            return;
        // that_row.querySelector("#key").setAttribute('name', new_row.querySelector("#key").getAttribute('future-name'));
        that_row.querySelector("#value").setAttribute('name',new_row.querySelector("#value").getAttribute('future-name') );
        
        // new_row.querySelector("#key").setAttribute('future-name', editNameCount(new_row.querySelector("#key")));
        // new_row.querySelector("#key").value = '';
        new_row.querySelector("#value").setAttribute('future-name', editNameCount(new_row.querySelector("#value")));
        new_row.querySelector("#value").value = '';
        new_row.setAttribute('row-id', parseInt(this.parentNode.parentNode.getAttribute('row-id'))+1)
        
        this.classList.remove('btn-success');
        this.innerHTML = '<i class="voyager-trash"></i>';
        new_row.querySelector('.btn-success').onclick = this.onclick;
        this.onclick = removeRow;
        this.parentNode.parentNode.parentNode.appendChild(new_row);
    };

    function removeRow() {
        this.parentNode.parentNode.remove();
    }

    var deleteButtons = document.querySelectorAll('.list-formfield .btn-delete');
    for (var i = 0; i < deleteButtons.length; i++) deleteButtons[i].onclick = removeRow;


    var addButtons = document.querySelectorAll('.list-formfield .btn-add');
    addButtons[addButtons.length - 1].onclick = addRow;
    
</script>


