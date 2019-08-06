@alert(['type' => 'info', 'icon' => 'fe fe-info', 'dismiss' => true, 'class' => 'mb-0'])
    @slot('title')
        {{ $announcement['name'] }}
    @endslot
    {!! Illuminate\Mail\Markdown::parse(e($announcement['description'])) !!}
@endalert
