@foreach(['error', 'warning', 'info', 'success', 'primary', 'secondary', 'light', 'dark'] as $messageType)

    @if(session()->has($messageType))
        @alert(['type' => $messageType, 'dismiss' => true])
            {{ session($messageType) }}
        @endalert
    @endif

@endforeach
