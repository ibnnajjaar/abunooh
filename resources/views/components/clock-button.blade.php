@props([
    'label',
    'visible' => true,
    'color' => 'gray'
])

@if ($visible)
    <button
        {{ $attributes }}
        @class([
            'w-full text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center',
            'bg-slate-800 hover:bg-slate-900' => $color === 'slate',
            'bg-gray-800 hover:bg-gray-900' => $color === 'gray',
            'bg-zinc-800 hover:bg-zinc-900' => $color === 'zinc',
            'bg-neutral-800 hover:bg-neutral-900' => $color === 'neutral',
            'bg-stone-800 hover:bg-stone-900' => $color === 'stone',
            'bg-red-800 hover:bg-red-900' => $color === 'red',
            'bg-orange-800 hover:bg-orange-900' => $color === 'orange',
            'bg-amber-800 hover:bg-amber-900' => $color === 'amber',
            'bg-yellow-800 hover:bg-yellow-900' => $color === 'yellow',
            'bg-lime-800 hover:bg-lime-900' => $color === 'lime',
            'bg-green-800 hover:bg-green-900' => $color === 'green',
            'bg-emerald-800 hover:bg-emerald-900' => $color === 'emerald',
            'bg-teal-800 hover:bg-teal-900' => $color === 'teal',
            'bg-cyan-800 hover:bg-cyan-900' => $color === 'cyan',
            'bg-sky-800 hover:bg-sky-900' => $color === 'sky',
            'bg-blue-800 hover:bg-blue-900' => $color === 'blue',
            'bg-indigo-800 hover:bg-indigo-900' => $color === 'indigo',
            'bg-violet-800 hover:bg-violet-900' => $color === 'violet',
            'bg-purple-800 hover:bg-purple-900' => $color === 'purple',
            'bg-fuchsia-800 hover:bg-fuchsia-900' => $color === 'fuchsia',
            'bg-pink-800 hover:bg-pink-900' => $color === 'pink',
            'bg-rose-800 hover:bg-rose-900' => $color === 'rose',
        ])>
    {{ $slot }}
    </button>
@endif
