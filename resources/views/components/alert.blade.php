<div class="alert alert-{{ ($type === 'error' ? 'danger' : $type) }} alert-dismissible fade show" role="alert">

    @isset($title)
        <h4 class="alert-heading">{{ $title }}</h4>
    @endisset

    {{ $slot }}

    @isset($dismiss)
    <button type="button" class="close" data-dismiss="alert" aria-label="{{ _('Close') }}">
        <span aria-hidden="true">&times;</span>
    </button>
    @endisset
</div>
