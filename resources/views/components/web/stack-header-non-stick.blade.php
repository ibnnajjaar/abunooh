@props([
    'index',
    'label',
])

<div {{ $attributes->merge(['class' => 'stack-header']) }}>
    <div class="junction bl"></div>
    <div class="junction br"></div>
    <span class="mono text-[13px] font-bold border-r border-[var(--grid)] h-full flex items-center justify-center">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
    <span class="technical-label px-6">{{ $label }}</span>
</div>
