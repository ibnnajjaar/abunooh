@props([
    'link' => '#'
])
<a
    @class([
        'inline-block text-lg py-4 font-semibold hover:text-orange-500 cursor-pointer',
        ' text-orange-500' => request()->url() == $link,
        ' dark:text-white text-slate-700 ' => request()->url() != $link,
    ])
    href="{{ $link }}">{{ str($slot)->title() }}</a>
