<div class="row">
    <x-voyager::panel  :header="false" >
        @section('submit-buttons')
            <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
            @if (!empty($actions))
                @foreach($actions as $action)
                    @if (!method_exists($action, 'massAction'))
                        @include('voyager::bread.partials.edit-add.action', ['action' => $action])
                    @endif
                @endforeach
            @endif
        @stop
        @yield('submit-buttons')
    </x-voyager::panel>
</div>

