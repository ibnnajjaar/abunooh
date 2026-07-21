@extends('layouts.web')

@section('content')
    <article class="relative">
        <header class="py-20 px-8 border-b border-[var(--grid)] bg-[var(--surface-high)] relative overflow-hidden">
            <div class="junction tl"></div>
            <div class="junction tr"></div>
            <div class="junction bl"></div>
            <div class="junction br"></div>

            <!-- Header Accents -->
            <div class="absolute left-[31.25%] top-0 bottom-0 w-[1px] bg-[var(--grid)] opacity-20 hidden md:block"></div>
            <div class="absolute right-[31.25%] top-0 bottom-0 w-[1px] bg-[var(--grid)] opacity-20 hidden md:block"></div>

            <div class="max-w-4xl mx-auto relative z-10">
                <div class="flex items-center gap-4 mb-8">
                    <span class="technical-label text-[11px] px-2 bg-[var(--lime)] text-ink mono">PAGE_{{ $page->id }}</span>
                    <span class="technical-label text-[11px] opacity-40 mono">SYSTEM // STATIC_NODE</span>
                </div>

                <h1 class="hero-headline text-ink mb-10">
                    {{ $page->title }}
                </h1>
            </div>
        </header>

        <div class="max-w-4xl mx-auto py-20 px-8">
            <div class="post-content line-numbers">
                {!! $page->formatted_content !!}
            </div>
        </div>

        <footer class="border-t border-[var(--grid)] py-20 px-8 bg-[var(--surface)] relative">
             <div class="junction tl"></div>
             <div class="junction tr"></div>
             <div class="max-w-4xl mx-auto flex justify-between items-center">
                 <a href="{{ route('web.home.index') }}" class="pill pill-pale group">
                    <span class="group-hover:-translate-x-1 transition-transform">←</span> Return to Hub
                 </a>
             </div>
        </footer>
    </article>
@endsection
