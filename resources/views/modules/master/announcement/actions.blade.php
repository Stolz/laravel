<div class="btn-group" role="group" aria-label="{{ _('Actions') }}">
    @can('view', $announcement)
        <a href="{{ route('master.announcement.show', [$announcement['id']]) }}" class="btn btn-info">
            <i class="fe fe-eye"></i>
            {{ _('View') }}
        </a>
    @else
        <a href="#" class="btn btn-info disabled">
            <i class="fe fe-eye"></i>
            {{ _('View') }}
        </a>
    @endcan

    @can('update', $announcement)
        <a href="{{ route('master.announcement.edit', [$announcement['id']]) }}" class="btn btn-primary">
            <i class="fe fe-edit-2"></i>
            {{ _('Edit') }}
        </a>
    @else
        <a href="#" class="btn btn-primary disabled">
            <i class="fe fe-edit-2"></i>
            {{ _('Edit') }}
        </a>
    @endcan

    @can('delete', $announcement)
        <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $announcement['id'] }}">
            <i class="fe fe-trash-2"></i>
            {{ _('Delete') }}
        </a>
    @else
        <a href="#" class="btn btn-danger disabled">
            <i class="fe fe-trash-2"></i>
            {{ _('Delete') }}
        </a>
    @endcan
</div>
