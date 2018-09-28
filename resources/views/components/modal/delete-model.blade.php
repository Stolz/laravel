<div id="delete-modal-{{ $model['id'] }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delete-modal-title-{{ $model['id'] }}"  aria-hidden="true">
    <form method="post" action="{{ $action }}" class="modal-dialog modal-dialog-centered" role="document" autocomplete="off">
        @csrf @method('delete')
        <input type="hidden" name="_from" value="{{ Route::currentRouteName() }}">

        <div class="modal-content">

            <div class="modal-header">
                <h5 id="delete-modal-title-{{ $model['id'] }}" class="modal-title">{{ $title or sprintf(_('Delete “%s”'), $model) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ _('Close') }}"></button>
            </div>

            <div class="modal-body text-center">
                @if(! method_exists($model, 'setDeletedAt'))
                    <p class="text-danger">{{ _('This action cannot be undone.') }}</p>
                @endif
                <p class="lead mb-0">{{ _('Do you want to proceed?') }}</p>
                {{ $slot }}
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fe fe-x"></i>
                    {{ _('Cancel') }}
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fe fe-trash-2"></i>
                    {{ _('Delete') }}
                </button>
            </div>
        </div>
    </form>
</div>
