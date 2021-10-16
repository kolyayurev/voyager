
@php
    $col    =  $col??12;
    $type   =  $type??"collapse";
    $fresh  =  $fresh??false;
    $title  =  $title??'';
    $header =  $header??true;
    $hidden =  $hidden??false;
    $visible=  count($dataTypeRows) && $dataTypeRows->whereIn('field',$fields)->count();
@endphp

@if ($visible)
<x-voyager::panel :col="$col" :type="$type" :fresh="$fresh" :title="$title" :header="$header" :hidden="$hidden">
    @foreach($fields as $field)
        @php
            $row = $dataTypeRows->where('field',$field)->first();
            if(empty($row))
                continue;
        @endphp
        @include('voyager::bread.partials.edit-row')
    @endforeach
</x-voyager::panel>
@endif
