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
    <div class="cursor-glow"></div>
    <div class="framed-canvas">
        <!-- Junctions -->
        <div class="junction tl"></div>
        <div class="junction tr"></div>

        <x-navigation />
        <div id="loader" class="fixed inset-0 z-[200] bg-[var(--canvas)] flex items-center justify-center transition-opacity duration-700">
            <div class="flex flex-col items-center">
                <div class="w-16 h-[1px] bg-[var(--grid)] relative overflow-hidden">
                    <div class="absolute inset-0 bg-[var(--lime)] animate-loader-progress"></div>
                </div>
                <span class="technical-label mt-4 text-[10px] opacity-50 tracking-[0.2em] animate-pulse">Initializing Connectivity</span>
            </div>
        </div>

        <main>
            @yield('content')
        </main>

        <footer class="border-t border-[var(--grid)] bg-[var(--surface)] p-[var(--page-gutter)] relative">
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

    <script>
        window.addEventListener('load', () => {
            const loader = document.getElementById('loader');
            if (loader) {
                loader.classList.add('opacity-0');
                setTimeout(() => loader.remove(), 700);
            }
        });

        const glow = document.querySelector('.cursor-glow');
        let x = -500, y = -500, frame = 0;

        function paintGlow() {
            glow.style.transform = `translate3d(${x}px, ${y}px, 0)`;
            frame = 0;
        }

        window.addEventListener('pointermove', (event) => {
            if (event.pointerType === 'touch') return;
            x = event.clientX;
            y = event.clientY;

            glow.classList.add('visible');

            if (!frame) frame = requestAnimationFrame(paintGlow);
        }, { passive: true });

        document.documentElement.addEventListener('mouseleave', () => {
            glow.classList.remove('visible');
        });

        // Scroll Reveal logic
        const revealCallback = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        };

        const revealObserver = new IntersectionObserver(revealCallback, {
            threshold: 0.01,
            rootMargin: '0px 0px -10px 0px'
        });

        const eagerObserver = new IntersectionObserver(revealCallback, {
            threshold: 0,
            rootMargin: '200px 0px 0px 0px'
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.reveal').forEach((el, i) => {
                el.style.setProperty('--delay', `${(i % 3) * 0.1}s`);
                if (el.classList.contains('reveal-eager')) {
                    eagerObserver.observe(el);
                } else {
                    revealObserver.observe(el);
                }
            });
        });
    </script>

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

        @keyframes loader-progress {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .animate-loader-progress {
            animation: loader-progress 1.5s var(--ease-editorial) infinite;
        }

        .cursor-glow {
            position: fixed;
            left: 0;
            top: 0;
            width: 400px;
            height: 400px;
            margin: -200px 0 0 -200px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
            opacity: 0;
            transform: translate3d(-500px, -500px, 0);
            background: radial-gradient(circle,
                oklch(70.5% .213 47.604 / 15%) 0%,
                oklch(70.5% .213 47.604 / 5%) 45%,
                transparent 70%);
            filter: blur(15px);
            mix-blend-mode: multiply;
            transition: opacity .5s;
            will-change: transform;
        }

        .cursor-glow.visible { opacity: 1; }
        .dark .cursor-glow {
            mix-blend-mode: screen;
            background: radial-gradient(circle,
                oklch(70.5% .213 47.604 / 8%) 0%,
                oklch(70.5% .213 47.604 / 3%) 35%,
                transparent 70%);
        }

        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s var(--ease-editorial), transform 0.8s var(--ease-editorial);
            transition-delay: var(--delay, 0s);
            will-change: transform, opacity;
        }

        .reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</body>
</html>
