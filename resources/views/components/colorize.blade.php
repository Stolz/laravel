<?php
$min = (isset($min)) ? $min : 0;   // Use a higher value to avoid too dark colors
$max = (isset($max)) ? $max : 255; // Use a lower value to avoid too light colors
?>
<span class="colorize" data-color="{{ colorize($slot, $min, $max) }}">{{ $slot }}</span>
