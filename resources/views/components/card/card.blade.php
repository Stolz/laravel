<div class="card {{ $class or null }}">
    @isset($status)
        <div class="card-status {{ $status }}"></div>{{-- i.e: card-status-left bg-blue --}}
    @endisset

    @isset($header)
        <div class="card-header {{ $headerClass or null }}">{{ $header }}</div>
    @endisset

    <div class="card-body {{ $bodyClass or null }}">{{ $slot }}</div>

    @isset($footer)
        <div class="card-footer {{ $footerClass or null }}">{{ $footer }}</div>
    @endisset
</div><!--.card-->
