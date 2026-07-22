@extends('layouts.web')

@section('content')
    <x-web.hero>
        Projects // Connectivity
    </x-web.hero>

    <div class="section-stack reveal">
        @foreach ($project_groups as $year => $projects)
            <x-web.stack-header
                :index="$loop->index"
                :label="$year . ' // Projects'"
            />

            <section id="projects-{{ $year }}"
                @class([
                    'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[1px] bg-[var(--grid)] relative',
                ])
            >
                <div class="grid-line-beam hidden lg:block"></div>
                <div class="junction bl"></div>
                <div class="junction br"></div>

                @foreach ($projects as $project)
                    <div class="kong-card reveal flex flex-col justify-between group min-h-[400px]">
                        <div class="junction tl"></div>
                        <div class="junction tr"></div>
                        <div class="junction bl"></div>
                        <div class="junction br"></div>
                        <div>
                            <span class="technical-label text-[11px] opacity-60 mono">
                                {{ $project->year }} // {{ $project->client }}
                            </span>
                            <h3 class="font-bold text-3xl mt-12 leading-tight group-hover:text-ink transition-colors">
                                {{ $project->title }}
                            </h3>
                            <div class="mt-6 text-[18px] line-clamp-4 text-[var(--text)] leading-relaxed prose-sm">
                                {!! $project->formatted_description !!}
                            </div>
                        </div>
                        <div class="mt-12 flex items-center gap-3 font-bold uppercase tracking-[0.2em] text-[11px] group-hover:text-ink transition-all mono">
                            {{ $project->status ?? 'Completed' }}
                            <span class="group-hover:translate-x-2 transition-transform text-[var(--lime)]">→</span>
                        </div>
                    </div>
                @endforeach
            </section>
        @endforeach
    </div>
@endsection
