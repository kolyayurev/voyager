@php
    if (is_array($dataTypeContent->{$row->field})) {
        $files = $dataTypeContent->{$row->field};
    } else {
        $files = json_decode($dataTypeContent->{$row->field});
    }
@endphp
@if ($files)
    @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
        @foreach (array_slice($files, 0, 3) as $file)
        <img src="@if( !filter_var($file, FILTER_VALIDATE_URL)){{ Voyager::image( $file ) }}@else{{ $file }}@endif" style="width:50px">
        @endforeach
    @else
        <ul>
        @foreach (array_slice($files, 0, 3) as $file)
            <li>{{ $file }}</li>
        @endforeach
        </ul>
    @endif
    @if (count($files) > 3)
        {{ __('voyager::media.files_more', ['count' => (count($files) - 3)]) }}
    @endif
@elseif (is_array($files) && count($files) == 0)
    {{ trans_choice('voyager::media.files_none') }}
@elseif ($dataTypeContent->{$row->field} != '')
    @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
        <img src="@if( !filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $dataTypeContent->{$row->field} ) }}@else{{ $dataTypeContent->{$row->field} }}@endif" style="width:50px">
    @else
        {{ $dataTypeContent->{$row->field} }}
    @endif
@else
    {{ trans_choice('voyager::media.files_none') }}
@endif