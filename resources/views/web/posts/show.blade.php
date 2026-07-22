@extends('layouts.web')

@section('content')
    <article class="relative">
        <header class="reveal reveal-eager py-20 px-8 border-b border-[var(--grid)] bg-[var(--surface-high)] relative overflow-hidden">
            <div class="junction tl"></div>
            <div class="junction tr"></div>
            <div class="junction bl"></div>
            <div class="junction br"></div>

            <!-- Header Accents -->
            <div class="absolute left-[31.25%] top-0 bottom-0 w-[1px] bg-[var(--grid)] opacity-20 hidden md:block"></div>
            <div class="absolute right-[31.25%] top-0 bottom-0 w-[1px] bg-[var(--grid)] opacity-20 hidden md:block"></div>

            <div class="max-w-4xl mx-auto relative z-10">
                <div class="flex items-center gap-4 mb-8">
                    <span class="technical-label text-[11px] px-2 bg-[var(--lime)] text-ink mono">ENTRY_{{ $post->id }}</span>
                    <span class="technical-label text-[11px] opacity-40 mono">{{ $post->formatted_long_publish_date }}</span>
                </div>

                <h1 class="post-headline text-ink mb-10">
                    {{ $post->title }}
                </h1>

                @if($post->excerpt)
                    <p class="text-2xl italic text-[var(--text)] font-light leading-relaxed max-w-3xl">
                        {{ $post->excerpt }}
                    </p>
                @endif

                <div class="mt-12 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-[var(--grid)] overflow-hidden border border-[var(--grid)]">
                        <!-- Author image placeholder if needed -->
                    </div>
                    <div>
                        <span class="technical-label text-[12px] block text-ink">{{ $post->author?->name }}</span>
                        <span class="technical-label text-[10px] opacity-40 block">Lead Architect // abunooh.com</span>
                    </div>
                </div>
            </div>
        </header>

        <div class="reveal reveal-eager max-w-4xl mx-auto py-20 px-8">
            <div class="post-content line-numbers">
                {!! $post->formatted_content !!}
            </div>
        </div>

        <footer class="border-t border-[var(--grid)] py-20 px-8 bg-[var(--surface)] relative">
             <div class="junction tl"></div>
             <div class="junction tr"></div>
             <div class="max-w-4xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-8">
                 <a href="{{ route('web.home.index') }}" class="pill pill-pale group">
                    <span class="group-hover:-translate-x-1 transition-transform">←</span> Return to Repository
                 </a>

                 <div class="flex items-center gap-6">
                     <span class="technical-label text-[11px] opacity-40">Distribute Node:</span>
                     <div class="flex gap-4 text-[13px] font-bold uppercase mono">
                        <a href="https://x.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(request()->url()) }}" target="_blank" class="hover:text-ink hover:underline decoration-[var(--lime)] decoration-2 underline-offset-4">X/TW</a>
                        <a href="#" class="hover:text-ink hover:underline decoration-[var(--lime)] decoration-2 underline-offset-4">LI</a>
                     </div>
                 </div>
             </div>
        </footer>
    </article>
@endsection
