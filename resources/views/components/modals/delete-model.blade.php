<div id="delete-modal-{{ $model['id'] }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delete-modal-title-{{ $model['id'] }}"  aria-hidden="true">
    <form method="post" action="{{ $action }}" class="modal-dialog modal-dialog-centered" role="document">
        @csrf @method('delete')

        <div class="modal-content">

            <div class="modal-header">
                <h5 id="delete-modal-title-{{ $model['id'] }}" class="modal-title">{{ $title or sprintf(_('Delete %s'), $model) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ _('Close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="lead">{{ _('Do you want to proceed?') }}</p>
                {{ $slot }}
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-raised btn-secondary" data-dismiss="modal">
                    {{ _('Cancel') }}
                </button>
                <button type="submit" class="btn btn-raised btn-danger">
                    {{ _('Delete') }}
                </button>
            </div>
        </div>
    </form>
</div>
