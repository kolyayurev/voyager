@php
if ($dataTypeContent->{$row->field.'_read'}) {
    $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_read'};
}
$rowType = 'read';
@endphp
<div class="panel-heading" style="border-bottom:0;">
    <h3 class="panel-title">{{ $row->getTranslatedAttribute('display_name') }}</h3>
</div>

<div class="panel-body" style="padding-top:0;">
    @if (isset($row->details->view))
        @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => 'read', 'view' => 'read', 'options' => $row->details])
    @elseif($row->type == 'relationship')
            @include('voyager::formfields.relationship', ['view' => 'read', 'options' => $row->details])
    @else
        {!! app('voyager')->formField($row, $dataType, $dataTypeContent,$rowType) !!}
    @endif
</div><!-- panel-body -->
@if(!$loop->last)
    <hr style="margin:0;">
@endif