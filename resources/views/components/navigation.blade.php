<nav class="site-nav px-4 md:px-6">
    <div class="junction bl"></div>
    <div class="junction br"></div>
    <div class="">
        <a href="{{ route('web.home.index') }}"
           class="font-extrabold text-xl md:text-2xl tracking-tighter text-ink">
            <span class="hidden md:block">ABU NOOH</span>
            <span class="block md:hidden">AN</span>
        </a>
    </div>
    <div class="flex items-center gap-6 text-base">
        <a href="{{ route('web.home.index') }}" class="technical-label hover:text-ink transition-colors">Blog</a>
        <a href="{{ route('web.projects.index') }}" class="technical-label hover:text-ink transition-colors">Projects</a>
        @php
            $menu_items = \App\Models\Post::query()
                ->published()
                ->pastSchedule()
                ->where('post_type', \App\Support\Enums\PostTypes::PAGE)
                ->where('is_menu_item', true)
                ->select('slug', 'title')
                ->get();
        @endphp
        @foreach ($menu_items as $menu_item)
            <a href="{{ route('web.posts.show', $menu_item->slug) }}" class="technical-label hover:text-ink transition-colors">{{ $menu_item->title }}</a>
        @endforeach

        <button
            onclick="window.toggleTheme()"
            class="technical-label hover:text-ink transition-colors p-2 rounded-full hover:bg-[oklch(70.5%_.213_47.604_/_0.1)] focus:outline-none"
            aria-label="Toggle Dark Mode"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </button>
    </div>
</nav>
