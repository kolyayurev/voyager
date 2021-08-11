{{-- 
    $options = [
        'class', // string
        'title', // string
        'url', // string
        'color', // css color - #ffffff|white
        'icon', // voyager icon class name - ['home','$default'...]
        'target', // ['_self','_blank']
    ]
    --}}
<a href="{{ $button->getUrl() }}" 
    class="admin-controls__button {{ $button->getOption('class') }}" 
    title="{{ $button->getTitle() }}" 
    style="
        @if ($button->hasColor())  background-color: {{$button->getColor()}}; @endif
        @if ($button->hasOption('fontColor'))  color: {{$button->getOption('fontColor')}}; @endif
        " 
    target="{{ $button->getOption('target','_self') }}"
>
    @if($button->hasIcon())
        <i class="voyager-{{$button->geticon()}}"></i>
    @else
    {{ Str::of($button->getTitle())->substr(0,1)->upper() }}
    @endif
</a>