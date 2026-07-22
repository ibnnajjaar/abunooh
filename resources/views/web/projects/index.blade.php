@extends('layouts.web')

@section('content')
    <x-web.hero>
        Projects // Connectivity
    </x-web.hero>

    <div class="section-stack reveal">
        @foreach ($project_groups as $year => $projects)
            <x-web.stack-header-non-stick
                :index="$loop->index"
                :label="$year . ' // Projects'"
            />

            <section id="projects-{{ $year }}"
                @class([
                    'project-card grid grid-cols-1 md:grid-cols-2 gap-[1px] bg-[var(--grid)] relative',
                ])
            >
                <div class="grid-line-beam hidden lg:block"></div>
                <div class="junction bl"></div>
                <div class="junction br"></div>

                @foreach ($projects as $project)
                    <div class="kong-card reveal flex flex-col justify-between group h-auto">
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
                            <div class="mt-6 text-[18px] text-[var(--text)] leading-relaxed prose-sm">
                                {!! $project->formatted_description !!}
                            </div>
                            @if($project->tags->isNotEmpty())
                                <div class="mt-6 flex flex-wrap gap-2">
                                    @foreach($project->tags as $tag)
                                        <span class="technical-label text-[10px] px-2 py-1 bg-[var(--grid)] border border-[var(--grid)] opacity-80 mono">
                                            #{{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="mt-12 inline-flex items-center gap-2 technical-label text-[10px] mono opacity-80 relative overflow-hidden px-2 py-1 transition-colors duration-300 group-hover:text-ink group-hover:opacity-100">
                            <span class="absolute inset-0 bg-[var(--lime)] -translate-x-full group-hover:translate-x-0 transition-transform duration-300 ease-out -z-10"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-[var(--lime)] group-hover:bg-ink animate-pulse transition-colors duration-300"></span>
                            {{ $project->status ?? 'Completed' }}
                        </div>
                    </div>
                @endforeach
            </section>
        @endforeach
    </div>
@endsection
