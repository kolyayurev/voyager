@can('browse_admin')

    <div class="admin-controls">
        <div class="admin-controls__container">
            @stack('admin-controls')
            <a href="{{ route('voyager.dashboard') }}" class="admin-controls__button -light">
                {{auth()->user()->name}}
                <span class="voyager-people"></span>
            </a>
        </div>
    </div>
@endcan