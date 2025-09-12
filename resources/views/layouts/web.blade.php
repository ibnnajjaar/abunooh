<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TNB382RXF1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-TNB382RXF1');
    </script>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;1,100;1,200;1,300;1,400;1,500&family=Outfit:wght@100;200;300;400;500;600&display=swap"
        rel="stylesheet">
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
        <meta property="og:description" content="Hussain Afeef is a Laravel developer at Javaabu.">
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
<body class="min-h-screen">
<div class="min-h-screen bg-gray-50 dark:bg-neutral-800">
    <div class="bg-red-400 text-white text-center p-4">
        Yo! Mandatory yearly update incoming. If you’re seeing this banner, expect some bugs — because what’s programming without debugging in production? Gotta update the site before I can even think about blogging!
    </div>
    <div class="max-w-screen-lg mx-auto min-h-screen px-4">
        <x-web.header/>
        @yield('content')
        <div class="flex justify-center items-center py-10 text-slate-500 dark:text-slate-400 font-light text-lg">
            {{ __("All Rights Reserved. :year © Hussain Afeef", ['year' => now()->format('Y')]) }}
        </div>
    </div>
</div>
@livewire('notifications')
</body>
</html>
