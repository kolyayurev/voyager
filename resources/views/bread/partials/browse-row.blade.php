@php
if ($data->{$row->field.'_browse'}) {
    $data->{$row->field} = $data->{$row->field.'_browse'};
}
$rowType = 'browse';
@endphp
<td>
    @if (isset($row->details->view))
        @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $data->{$row->field}, 'action' => 'browse', 'view' => 'browse', 'options' => $row->details])
    @elseif($row->type == 'relationship')
        @include('voyager::formfields.relationship', ['view' => 'browse','options' => $row->details])
    @else
        {!! app('voyager')->formField($row, $dataType, $data ,$rowType) !!}
    @endif
</td>