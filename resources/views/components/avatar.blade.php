<?php
$pixels = [
    'sm' => 24,
    'default' => 32, // default
    'md' => 40,
    'lg' => 48,
    'xl' => 64,
    'xxl' => 80,
];
$size = $size ?? 'default';
$url = $user->getAvatar($pixels[$size] ?? $pixels['default']);
?>
<span class="avatar avatar-{{ $size }} {{ $class ?? null }}" style="background-image: url({{ $url }})">
    {{ $slot }}
</span><!--.avatar-->
