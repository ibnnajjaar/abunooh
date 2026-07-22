<section {{ $attributes->merge(['class' => 'hero reveal min-h-[250px] flex flex-col items-center justify-center text-center py-12 px-8 relative overflow-hidden']) }}>
    <div class="junction tl"></div>
    <div class="junction tr"></div>
    <div class="junction bl"></div>
    <div class="junction br"></div>

    <div class="grid-line-beam hidden md:block"></div>

    <!-- Hero Accents -->
    <div class="absolute left-[31.25%] top-0 bottom-0 w-[1px] bg-[var(--grid)] opacity-30 hidden md:block"></div>
    <div class="absolute right-[31.25%] top-0 bottom-0 w-[1px] bg-[var(--grid)] opacity-30 hidden md:block"></div>

    <div class="relative z-10">
        <h1 class="hero-headline uppercase mb-6">
            {{ $slot }}
        </h1>
    </div>
</section>
