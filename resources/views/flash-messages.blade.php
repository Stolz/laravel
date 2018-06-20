@foreach(['error', 'warning', 'info', 'success', 'primary', 'secondary', 'light', 'dark'] as $flashMessageType)

    @if(session()->has($flashMessageType))
        @alert(['type' => $flashMessageType, 'dismiss' => true])
            {{ session($flashMessageType) }}
        @endalert
    @endif

@endforeach
