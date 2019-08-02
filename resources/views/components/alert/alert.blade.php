<?php
    $icons = ['info' => 'fe fe-info', 'success' => 'fe fe-check-circle', 'warning' => 'fe fe-alert-triangle', 'error' => 'fe fe-alert-octagon'];

    // Show icon by default
    if (! isset($icon))
        $icon = $icons[$type] ?? 'fe fe-alert-circle';
?>
<div class="alert {{ $class ?? null }}
    alert-{{ ($type === 'error' ? 'danger' : $type) }}
    @if($icon ?? true) alert-icon @endif
    @isset($dismiss) alert-dismissible @endisset
    " role="alert">

    @isset($dismiss)
        <button type="button" class="close" data-dismiss="alert" aria-label="{{ _('Close') }}"></button>
    @endisset

    @isset($title)
        <div class="alert-heading h4">{{ $title }}</div>
    @endisset

    @if($icon)
        <i class="{{ $icon }}" aria-hidden="true"></i>
    @endif

    {{ $slot }}

</div>
