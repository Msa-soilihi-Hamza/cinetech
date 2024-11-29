@props([
    'animation' => 'fade-up',
    'duration' => 1000,
    'delay' => 0,
    'once' => false
])

<div {{ $attributes->merge([
    'data-aos' => $animation,
    'data-aos-duration' => $duration,
    'data-aos-delay' => $delay,
    'data-aos-once' => $once
]) }}>
    {{ $slot }}
</div> 