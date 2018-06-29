<div class="table-responsive">
    <table class="table table-striped table-hover {{ $class or null }}" summary="">
        @isset($caption)
        <caption>{{ $caption }}</caption>
        @endisset

        @isset($header)
        <thead>
            {{ $header }}
        </thead>
        @endisset

        <tbody>
            {{ $slot }}
        </tbody>

        @isset($footer)
        <tfoot>
            {{ $footer }}
        </tfoot>
        @endisset
    </table>
</div>
