@props([
    'color' => 'gray'
])

<span class="relative mr-2 inline-flex items-center justify-center">
    {{ $slot }}
    <svg x-show="isPressing || progress > 0" class="absolute -inset-1" width="28" height="28"
         viewBox="0 0 36 36" aria-hidden="true">
        <circle cx="18" cy="18" r="16" fill="none" stroke="rgba(255,255,255,0.25)"
                stroke-width="3"/>
        <circle cx="18" cy="18" r="16" fill="none"
                @class([
                    'stroke-slate-600' => $color === 'slate',
                    'stroke-gray-600' => $color === 'gray',
                    'stroke-zinc-600' => $color === 'zinc',
                    'stroke-neutral-600' => $color === 'neutral',
                    'stroke-stone-600' => $color === 'stone',
                    'stroke-red-600' => $color === 'red',
                    'stroke-orange-600' => $color === 'orange',
                    'stroke-amber-600' => $color === 'amber',
                    'stroke-yellow-600' => $color === 'yellow',
                    'stroke-lime-600' => $color === 'lime',
                    'stroke-green-600' => $color === 'green',
                    'stroke-emerald-600' => $color === 'emerald',
                    'stroke-teal-600' => $color === 'teal',
                    'stroke-cyan-600' => $color === 'cyan',
                    'stroke-sky-600' => $color === 'sky',
                    'stroke-blue-600' => $color === 'blue',
                    'stroke-indigo-600' => $color === 'indigo',
                    'stroke-violet-600' => $color === 'violet',
                    'stroke-purple-600' => $color === 'purple',
                    'stroke-fuchsia-600' => $color === 'fuchsia',
                    'stroke-pink-600' => $color === 'pink',
                    'stroke-rose-600' => $color === 'rose',
                ])
                stroke-linecap="round" stroke-width="3" stroke-dasharray="100"
                :stroke-dashoffset="100 - (progress * 100)" transform="rotate(-90 18 18)"/>
    </svg>
</span>
