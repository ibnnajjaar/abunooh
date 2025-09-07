@props([
    'title',
    'date',
    'link' => '#',
])
<a class="group block cursor-pointer py-3 mb-2 rounded-lg"
   href="{{ $link }}">
    <div class="grid grid-cols-8">
        <div class="title text-lg font-medium text-gray-700 group-hover:text-orange-500 dark:text-white col-span-7">{{ $title }}</div>
        <div class="date col-span-1 text-right font-light text-zinc-700 dark:text-slate-200 group-hover:text-orange-500">{{ $date }}</div>
    </div>
    <div class="excerpt text-lg text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-200 font-light mt-2">{{ $slot }}</div>
</a>
