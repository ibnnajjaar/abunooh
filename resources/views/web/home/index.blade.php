@extends('layouts.web')

@section('content')
    <section class="hero min-h-[250px] flex flex-col items-center justify-center text-center py-12 px-8 relative overflow-hidden">
        <div class="junction tl"></div>
        <div class="junction tr"></div>
        <div class="junction bl"></div>
        <div class="junction br"></div>

        <!-- Hero Accents -->
        <div class="absolute left-[31.25%] top-0 bottom-0 w-[1px] bg-[var(--grid)] opacity-30 hidden md:block"></div>
        <div class="absolute right-[31.25%] top-0 bottom-0 w-[1px] bg-[var(--grid)] opacity-30 hidden md:block"></div>

        <div class="relative z-10">
            <h1 class="hero-headline uppercase mb-6">
                Great software <br /> begins with <br>
                <span class="bg-[var(--lime)] px-4">clear thinking.</span>
            </h1>
{{--            <p class="font-light text-xl max-w-2xl mx-auto text-[var(--text)] italic">--}}
{{--                Thoughts on Laravel, architecture, and building software that's simple, scalable, and built to last.--}}
{{--            </p>--}}
        </div>
    </section>

    <div class="section-stack">
        @foreach ($posts as $index => $year_group)
            <div class="stack-header" style="--index: {{ $index }}">
                <div class="junction bl"></div>
                <div class="junction br"></div>
                <span class="mono text-[13px] font-bold border-r border-[var(--grid)] h-full flex items-center justify-center">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="technical-label px-6">{{ $year_group['year'] }} // Journal</span>
                <span class="mono text-[var(--lime)] border-l border-[var(--grid)] h-full flex items-center justify-center">+</span>
            </div>

            <section id="articles-{{ $year_group['year'] }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[1px] bg-[var(--grid)] border-b border-[var(--grid)] relative">
                <div class="junction bl"></div>
                <div class="junction br"></div>
                @foreach ($year_group['posts'] as $post)
                    <a href="{{ route('web.posts.show', $post->slug) }}" class="kong-card flex flex-col justify-between group min-h-[400px]">
                         <div class="junction tl"></div>
                         <div class="junction tr"></div>
                         <div class="junction bl"></div>
                         <div class="junction br"></div>
                         <div>
                             <span class="technical-label text-[11px] opacity-60 mono">{{ $post->formatted_publish_date }}</span>
                             <h3 class="font-bold text-3xl mt-12 leading-tight group-hover:text-ink transition-colors">{{ $post->title }}</h3>
                             <p class="mt-6 text-[18px] line-clamp-3 text-[var(--text)] leading-relaxed">
                                 {{ $post->excerpt }}
                             </p>
                         </div>
                         <div class="mt-12 flex items-center gap-3 font-bold uppercase tracking-[0.2em] text-[11px] group-hover:text-ink transition-all mono">
                             Read Entry <span class="group-hover:translate-x-2 transition-transform text-[var(--lime)]">→</span>
                         </div>
                    </a>
                @endforeach
            </section>
        @endforeach
    </div>
@endsection
