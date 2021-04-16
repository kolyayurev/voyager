@if (!empty($data->{$row->field}))
    @include('voyager::multilingual.input-hidden-bread-browse')
    @if(json_decode($dataTypeContent->{$row->field}) !== null)
        @foreach(json_decode($dataTypeContent->{$row->field}) as $file)
            <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}" target="_blank">
                {{ $file->original_name ?: '' }}
            </a>
            <br/>
        @endforeach
    @else
        <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($dataTypeContent->{$row->field}) }}" target="_blank">
            Download
        </a>
    @endif
@endif
