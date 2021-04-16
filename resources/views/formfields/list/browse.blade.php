@include('voyager::multilingual.input-hidden-bread-browse')
@php
$list = $dataTypeContent->{$row->field};
@endphp
<ul>
@if (!empty($list))
    @foreach($list as $item)
        <li>{{ $item->text }}</li> 
    @endforeach
@endif
</ul>