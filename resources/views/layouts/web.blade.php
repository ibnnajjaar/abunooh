<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}"/>
    <title>{{ get_setting('site_name') }}</title>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @if (! request()->routeIs('web.posts.show'))
        <meta property="og:site_name" content="abunooh.com">
        <meta property="og:locale" content="en_US">
        <meta property="og:description" content="{{ get_setting('site_description') }}">
        <meta property="og:url" content="https://abunooh.com">
        <meta property="og:image" content="{{ asset('images/site_image.jpg') }}">
    @endif
    @if (request()->routeIs('web.posts.show'))
        <meta property="og:title" content="{{ $post->title ?? ''  }}">
        <meta property="og:description"
              content="{{ $post->excerpt ?? '' }}">
        <meta property="og:image" content="{{ $post->graphify_image ?? asset('images/site_image.jpg') }}">
        <meta property="article:published_time" content="{{ $post->published_at ?? '' }}">
        <meta property="og:updated_time" content="{{ $post->updated_at ?? '' }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:description"
              content="{{ $post->excerpt ?? '' }}">
        <meta name="twitter:title" content="{{ $post->title ?? ''  }}">
        <meta name="twitter:site" content="@hucenafeef">
        <meta name="twitter:image" content="{{ $post->graphify_image ?? asset('images/site_image.jpg') }}">
        <meta name="twitter:creator" content="@hucenafeef">
    @endif

    @vite(['resources/css/web.css', 'resources/js/web.js'])
    @livewireStyles
    @livewireScripts
    @stack('scripts')
</head>
<body class="antialiased selection:bg-[oklch(70.5%_.213_47.604)] selection:text-[#001408]">

    <div class="framed-canvas">
        <!-- Junctions -->
        <div class="junction tl"></div>
        <div class="junction tr"></div>

        <x-navigation />

        <main>
            @yield('content')
        </main>

        <footer class="mt-20 border-t border-[var(--grid)] bg-[var(--surface)] p-[var(--page-gutter)] relative">
            <div class="junction tl"></div>
            <div class="junction tr"></div>
            <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                <div>
                    <h2 class="font-extrabold text-3xl tracking-tighter text-ink mb-4">ABU NOOH</h2>
                    <p class="max-w-md text-[17px] leading-relaxed">
                        {{ get_setting('site_description') }}
                    </p>
                </div>
                <div class="flex flex-col gap-2">
                    <span class="technical-label">Connectivity</span>
                    <a href="https://github.com/hucenafeef" class="text-ink hover:underline">GitHub</a>
                </div>
            </div>
            <div class="mt-20 flex justify-between items-center text-[13px] uppercase tracking-widest text-text font-bold mono">
                <span>© {{ now()->year }} HUSSAIN AFEEF</span>
                <span>BUILT WITH LARAVEL + FILAMENT</span>
            </div>
        </footer>

        <div class="junction bl"></div>
        <div class="junction br"></div>
    </div>

    @livewire('notifications')

    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            animation: marquee 30s linear infinite;
        }
        @media (prefers-reduced-motion: reduce) {
            .animate-marquee {
                animation: none;
            }
        }
    </style>
</body>
</html>
