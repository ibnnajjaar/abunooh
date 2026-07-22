@extends('layouts.web')

@section('content')
    <x-web.hero>
        {!! get_setting('home_page_title') !!}
    </x-web.hero>

    <div class="section-stack">
        @foreach ($posts as $index => $year_group)
            <x-web.stack-header
                :index="$index"
                :label="$year_group['year'] . ' // Journal'"
            />

            <section id="articles-{{ $year_group['year'] }}"
                @class([
                    'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[1px] bg-[var(--grid)] relative',
                ])
            >
                <div class="junction bl"></div>
                <div class="junction br"></div>
                @foreach ($year_group['posts'] as $post)
                    <a href="{{ route('web.posts.show', $post->slug) }}"
                       class="kong-card flex flex-col justify-between group min-h-[400px]">
                        <div class="junction tl"></div>
                        <div class="junction tr"></div>
                        <div class="junction bl"></div>
                        <div class="junction br"></div>
                        <div>
                            <span
                                class="technical-label text-[11px] opacity-60 mono">{{ $post->formatted_publish_date }}</span>
                            <h3 class="font-bold text-3xl mt-12 leading-tight group-hover:text-ink transition-colors">{{ $post->title }}</h3>
                            <p class="mt-6 text-[18px] line-clamp-3 text-[var(--text)] leading-relaxed">
                                {{ $post->excerpt }}
                            </p>
                        </div>
                        <div
                            class="mt-12 flex items-center gap-3 font-bold uppercase tracking-[0.2em] text-[11px] group-hover:text-ink transition-all mono">
                            Read Entry <span
                                class="group-hover:translate-x-2 transition-transform text-[var(--lime)]">→</span>
                        </div>
                    </a>
                @endforeach
            </section>
        @endforeach
    </div>
@endsection
