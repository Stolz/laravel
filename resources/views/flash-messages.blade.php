@foreach(['error', 'warning', 'info', 'success'] as $flashMessageType)
    @if(session()->has($flashMessageType))
        <div class="{{ $flashMessageType }}">
            {{ session($flashMessageType) }}
        </div>
    @endif
@endforeach
