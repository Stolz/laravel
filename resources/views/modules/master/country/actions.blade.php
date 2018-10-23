<div class="btn-group" role="group" aria-label="{{ _('Actions') }}">
    @can('view', $country)
        <a href="{{ route('master.country.show', [$country['id']]) }}" class="btn btn-info">
            <i class="fe fe-eye"></i>
            {{ _('View') }}
        </a>
    @else
        <a href="#" class="btn btn-info disabled">
            <i class="fe fe-eye"></i>
            {{ _('View') }}
        </a>
    @endcan

    @can('update', $country)
        <a href="{{ route('master.country.edit', [$country['id']]) }}" class="btn btn-primary">
            <i class="fe fe-edit-2"></i>
            {{ _('Edit') }}
        </a>
    @else
        <a href="#" class="btn btn-primary disabled">
            <i class="fe fe-edit-2"></i>
            {{ _('Edit') }}
        </a>
    @endcan

    @can('delete', $country)
        <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $country['id'] }}">
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
