<div class="mt-10 dark:border-neutral-700 rounded-md relative">
    <input wire:model.debounce.500ms="search" type="text"
           v-model="search"
           class="focus:border-2 focus:border-orange-500 w-full px-4 py-2 pr-10 rounded-md border-2 border-gray-200
           dark:border-gray-500 dark:focus:border-orange-500 dark:text-slate-200 dark:bg-neutral-600 dark:outline-slate-200 dark:focus:ring-slate-200
           text-gray-500 font-light bg-white focus:ring-0 focus:outline-0"
           placeholder="Search articles">
    <svg xmlns="http://www.w3.org/2000/svg"
         class="h-6 w-6 text-gray-600 dark:text-orange-500 mr-2 absolute right-[4px] top-[10px]"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
</div>
