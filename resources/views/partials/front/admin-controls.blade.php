@if(config('voyager.controls_panel.enable') && auth()->check() && auth()->user()->can('browse_admin'))
    @php
        Voyager::addControlButton([
                    'url'=>route('voyager.dashboard'),
                    'color'=>config('voyager.primary_color'),
                    'fontColor'=>'white',
                    'icon'=>'home',
                    'title'=>'Админ панель'
                ]);
        $buttons = Voyager::getControlButtons();
        
    @endphp
    @if ($buttons->count())
    <div class="admin-controls {{ config('voyager.controls_panel.position') }} ">
        
        <button class="admin-controls__switch {{ Cookie::get('admin-controls-expanded','open') }}" id="adminControlsSwitch"></button>
        <div class="admin-controls__buttons {{ Cookie::get('admin-controls-expanded','open') }}">
            @foreach ($buttons as $button)
                @include('voyager::partials.front.button')
            @endforeach
            @stack('admin-controls')
        </div>
        
    </div>
    @endif
@endif