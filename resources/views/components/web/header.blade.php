<div class="flex flex-row justify-between py-10 items-center">
    <div class="space-x-6">
        <x-web.menu-item :link="route('web.home.index')">{{ __('Blog') }}</x-web.menu-item>
        <x-web.menu-item :link="route('web.projects.index')">{{ __('Portfolio') }}</x-web.menu-item>
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
            <x-web.menu-item :link="route('web.posts.show', $menu_item->slug)">{{ $menu_item->title }}</x-web.menu-item>
        @endforeach
    </div>
    <x-web.dark-mode-switcher />
</div>
