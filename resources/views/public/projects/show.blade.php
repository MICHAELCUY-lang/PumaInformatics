@extends('layouts.public')

@php
    use Illuminate\Support\Str;
    /** @var \App\Models\Project $project */
@endphp

@section('title', $project->title)
@section('meta_description', Str::limit(strip_tags($project->description), 150))
@section('meta_type', 'article')
@section('meta_image', $project->getFirstMediaUrl('hero', 'og') ?: null)

@push('seo')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "CreativeWork",
  "name": "{{ $project->title }}",
  "description": "{{ Str::limit(strip_tags($project->description), 150) }}",
  "image": [
    "{{ $project->getFirstMediaUrl('hero', 'og') ?: asset('images/default-og.jpg') }}"
  ],
  "author": {
    "@@type": "Organization",
    "name": "{{ config('app.name', 'PUMA IT') }}"
  }
}
</script>
@endpush

@section('content')
<div class="min-h-screen">

    <!-- Hero -->
    <div class="relative w-full h-[60vh] md:h-[80vh] bg-sapientia-ink overflow-hidden">
        @if($project->getFirstMediaUrl('hero'))
            <img src="{{ $project->getFirstMediaUrl('hero', 'hero') }}" alt="{{ $project->title }}" class="absolute inset-0 w-full h-full object-cover opacity-50">
        @endif
        <div class="absolute inset-0 bg-topo opacity-[0.18] mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-jp-indigo-deep/70 via-jp-indigo-deep/30 to-jp-cream"></div>
        
        <div class="absolute bottom-0 left-0 w-full pb-16 sm:pb-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center" x-data="{ shown: false }" x-intersect.once="shown = true">
                <div class="flex justify-center mb-6 reveal" :class="shown ? 'active' : ''">
                    <svg width="40" height="12" viewBox="0 0 40 12" fill="none"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#448AFF" stroke-width="1.5"/></svg>
                </div>
                <span class="inline-block px-4 py-1.5 bg-white/10 backdrop-blur-md text-jp-cream text-[10px] tracking-[0.25em] uppercase font-semibold mb-6 reveal" :class="shown ? 'active' : ''">
                    Case Study
                </span>
                <h1 class="font-serif text-5xl md:text-7xl text-jp-indigo mb-8 reveal reveal-delay-100" :class="shown ? 'active' : ''">
                    {{ $project->title }}
                </h1>
                
                <div class="flex flex-wrap justify-center items-center gap-6 text-jp-indigo/70 text-[11px] tracking-[0.2em] uppercase reveal reveal-delay-200" :class="shown ? 'active' : ''">
                    <div>
                        <span class="block text-jp-indigo/40 text-[9px] mb-1">Category</span>
                        <span class="font-semibold">{{ $project->category->name ?? 'Technology' }}</span>
                    </div>
                    <svg width="16" height="6" viewBox="0 0 16 6" fill="none"><path d="M0 3C3 1 5 1 8 3C11 5 13 5 16 3" stroke="#C5A47E" stroke-width="0.75"/></svg>
                    <div>
                        <span class="block text-jp-indigo/40 text-[9px] mb-1">Status</span>
                        <span class="font-semibold">{{ $project->status }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Section -->
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 -mt-12 relative z-10">
        <div class="bg-white shadow-wave p-12 md:p-16 grid grid-cols-1 md:grid-cols-3 gap-12 relative overflow-hidden">
            <!-- Gold top accent -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-jp-gold via-jp-indigo to-jp-gold"></div>
            
            <div class="md:col-span-2 prose prose-lg prose-headings:font-serif prose-headings:text-jp-indigo prose-p:text-jp-indigo/75 prose-strong:text-jp-indigo">
                {!! $project->description !!}
            </div>

            <div class="md:col-span-1 space-y-12 border-l border-jp-indigo/10 pl-8">
                @if($project->url || $project->github_url)
                    <div>
                        <h4 class="font-serif text-[10px] uppercase tracking-[0.25em] text-jp-gold font-semibold mb-4">Resources</h4>
                        <ul class="space-y-4">
                            @if($project->url)
                                <li>
                                    <a href="{{ $project->url }}" target="_blank" class="flex items-center gap-3 text-jp-indigo hover:text-jp-gold transition-colors duration-500 font-medium text-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        Live Demo
                                    </a>
                                </li>
                            @endif
                            @if($project->github_url)
                                <li>
                                    <a href="{{ $project->github_url }}" target="_blank" class="flex items-center gap-3 text-jp-indigo hover:text-jp-gold transition-colors duration-500 font-medium text-sm">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                        Source Repository
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
                
                @if($project->technologies->isNotEmpty())
                    <div>
                        <h4 class="font-serif text-[10px] uppercase tracking-[0.25em] text-jp-gold font-semibold mb-4">Technology Stack</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($project->technologies as $tech)
                                <span class="px-3 py-1 bg-jp-cream text-jp-indigo text-[10px] tracking-[0.15em] uppercase border border-jp-indigo/10">{{ $tech->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </section>

    <!-- Project Gallery -->
    @if($project->hasMedia('gallery'))
    <section class="py-12 relative pb-24">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-jp-indigo/10 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">
            <div class="text-center mb-12">
                <svg width="40" height="12" viewBox="0 0 40 12" fill="none" class="mx-auto mb-3"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#C5A47E" stroke-width="1"/></svg>
                <h3 class="font-serif text-[11px] uppercase tracking-[0.3em] text-jp-gold font-semibold">Project Gallery</h3>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-data="{ shown: false }" x-intersect.once="shown = true">
                @foreach($project->getMedia('gallery') as $media)
                    <div class="relative overflow-hidden aspect-square group reveal bg-jp-mist" :class="shown ? 'active' : ''" style="transition-delay: {{ $loop->index * 75 }}ms">
                        <img src="{{ $media->getUrl('showcase') }}" alt="{{ $project->title }} Gallery Image" class="object-cover w-full h-full transform group-hover:scale-110 transition-transform duration-700 ease-in-out cursor-pointer" @click="window.open('{{ $media->getUrl() }}', '_blank')">
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

</div>
@endsection
