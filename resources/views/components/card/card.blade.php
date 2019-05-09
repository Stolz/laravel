<div class="card {{ $class ?? null }}">
    @isset($status)
        <div class="card-status {{ $status }}"></div>{{-- i.e: card-status-left bg-blue --}}
    @endisset

    @isset($header)
        <div class="card-header {{ $headerClass ?? null }}">{{ $header }}</div>
    @endisset

    @isset($alert)
        <div class="card-alert alert {{ $alertClass ?? null }}">{{ $alert }}</div>
    @endisset

    <div class="card-body {{ $bodyClass ?? null }}">{{ $slot }}</div>

    @isset($footer)
        <div class="card-footer {{ $footerClass ?? null }}">{{ $footer }}</div>
    @endisset
</div><!--.card-->
