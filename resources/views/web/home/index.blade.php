@extends('layouts.web')

@section('content')
    <section class="hero min-h-[600px] flex flex-col items-center justify-center text-center border-b border-[var(--grid)] py-20 px-8 relative overflow-hidden bg-[var(--surface-high)]">
        <div class="junction tl"></div>
        <div class="junction tr"></div>
        <div class="junction bl"></div>
        <div class="junction br"></div>

        <!-- Hero Accents -->
        <div class="absolute left-[31.25%] top-0 bottom-0 w-[1px] bg-[var(--grid)] opacity-30 hidden md:block"></div>
        <div class="absolute right-[31.25%] top-0 bottom-0 w-[1px] bg-[var(--grid)] opacity-30 hidden md:block"></div>

        <div class="relative z-10">
            <h1 class="hero-headline mb-6">
                ENGINEERING <br>
                <span class="bg-[var(--lime)] px-4">ELEGANCE</span>
            </h1>

            <p class="font-light text-2xl max-w-2xl mx-auto mb-10 text-[var(--text)] italic">
                Hussain Afeef — Laravel Architect & Interface Specialist crafting industrial-grade web experiences.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                 <a href="#articles" class="pill pill-lime px-10 py-4 text-base">Explore Writing</a>
                 <a href="mailto:hussain@javaabu.com" class="pill pill-pale px-10 py-4 text-base">Secure Connectivity</a>
            </div>

            <div class="mt-16 technical-label text-[11px] opacity-40">
                SYSTEM STATUS: OPTIMIZED // LATENCY: 24MS // LOCATION: MLE
            </div>
        </div>
    </section>

    <div class="section-stack">
        <div class="stack-header" style="--index: 0">
            <span class="mono text-[13px] font-bold border-r border-[var(--grid)] h-full flex items-center justify-center">01</span>
            <span class="technical-label px-6">Archive // Journal</span>
            <span class="mono text-[var(--lime)] border-l border-[var(--grid)] h-full flex items-center justify-center">+</span>
        </div>

        <section id="articles" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[1px] bg-[var(--grid)] border-b border-[var(--grid)]">
            @foreach ($posts as $year_posts)
                @foreach ($year_posts['posts'] as $post)
                    <a href="{{ route('web.posts.show', $post->slug) }}" class="kong-card flex flex-col justify-between group min-h-[400px]">
                         <div class="junction tl"></div>
                         <div class="junction tr"></div>
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
            @endforeach
        </section>

        <div class="stack-header" style="--index: 1">
            <span class="mono text-[13px] font-bold border-r border-[var(--grid)] h-full flex items-center justify-center">02</span>
            <span class="technical-label px-6">Node // Contact</span>
            <span class="mono text-[var(--lime)] border-l border-[var(--grid)] h-full flex items-center justify-center">+</span>
        </div>

        <section class="bg-[var(--surface)] py-[var(--section-pad)] px-8 border-b border-[var(--grid)] text-center relative overflow-hidden">
             <div class="junction tl"></div>
             <div class="junction tr"></div>
             <div class="relative z-10">
                 <h2 class="section-statement mb-10">REQUEST <br> <span class="bg-[var(--lime)] px-4">DELEGATION</span></h2>
                 <p class="max-w-xl mx-auto text-xl mb-14 text-[var(--text)]">
                     Establishing a direct link for technical collaboration, system architecture, or full-stack Laravel development.
                 </p>
                 <a href="mailto:hussain@javaabu.com" class="pill pill-lime text-lg py-5 px-14">Initiate Handshake</a>
             </div>
             <!-- Ambient Decoration -->
             <div class="absolute inset-0 pointer-events-none opacity-5">
                 <div class="grid grid-cols-12 h-full w-full">
                     @for ($i = 0; $i < 12; $i++)
                         <div class="border-r border-[var(--ink)] h-full"></div>
                     @endfor
                 </div>
             </div>
        </section>
    </div>
@endsection
