@extends('layouts.public')

@php
    use Illuminate\Support\Str;
    /** @var \App\Models\Article $article */
    /** @var \Illuminate\Support\Collection<int, \App\Models\Article> $relatedArticles */
@endphp

@section('title', $article->title)
@section('meta_description', Str::limit(strip_tags($article->content), 150))
@section('meta_type', 'article')
@section('meta_image', $article->getFirstMediaUrl('cover') ?: null)

@push('seo')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Article",
  "headline": "{{ $article->title }}",
    "image": [
    "{{ $article->getFirstMediaUrl('cover') ?: asset('images/default-og.jpg') }}"
  ],
  "datePublished": "{{ $article->published_at->toIso8601String() }}",
  "dateModified": "{{ $article->updated_at->toIso8601String() }}",
  "author": [{
      "@@type": "Person",
      "name": "{{ $article->author->name ?? 'Staff' }}"
  }]
}
</script>
@endpush

@section('content')
<!-- Reading Progress Bar -->
<div class="fixed top-0 left-0 h-[2px] bg-gradient-to-r from-jp-gold to-jp-indigo z-[60] transition-all duration-75" id="reading-progress" style="width: 0%;"></div>

<div class="min-h-screen pb-24">

    <!-- Cinematic Cover -->
    <div class="relative w-full h-[60vh] md:h-[80vh] bg-sapientia-ink overflow-hidden">
        @if($article->getFirstMediaUrl('cover'))
            <img src="{{ $article->getFirstMediaUrl('cover') }}" alt="{{ $article->title }}" class="absolute inset-0 w-full h-full object-cover">
        @endif
        <div class="absolute inset-0 bg-topo opacity-[0.18] mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-jp-indigo-deep/90 via-jp-indigo-deep/30 to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 w-full pb-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" x-data="{ shown: false }" x-intersect.once="shown = true">
                <div class="flex justify-center mb-6 reveal" :class="shown ? 'active' : ''">
                    <svg width="40" height="12" viewBox="0 0 40 12" fill="none"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#C5A47E" stroke-width="1.5"/></svg>
                </div>
                <span class="inline-block px-4 py-1.5 bg-white/10 backdrop-blur-md text-jp-cream text-[10px] tracking-[0.25em] uppercase font-semibold mb-6 reveal" :class="shown ? 'active' : ''">
                    Editorial
                </span>
                <h1 class="font-serif text-4xl md:text-6xl text-jp-cream leading-tight mb-8 reveal reveal-delay-100" :class="shown ? 'active' : ''">
                    {{ $article->title }}
                </h1>
                <div class="flex items-center justify-center gap-4 text-jp-cream/60 text-[11px] tracking-[0.2em] uppercase reveal reveal-delay-200" :class="shown ? 'active' : ''">
                    <span>{{ $article->published_at->format('F j, Y') }}</span>
                    <svg width="16" height="6" viewBox="0 0 16 6" fill="none"><path d="M0 3C3 1 5 1 8 3C11 5 13 5 16 3" stroke="#C5A47E" stroke-width="0.75"/></svg>
                    <span>By {{ $article->author->name ?? 'Staff' }}</span>
                    <svg width="16" height="6" viewBox="0 0 16 6" fill="none"><path d="M0 3C3 1 5 1 8 3C11 5 13 5 16 3" stroke="#C5A47E" stroke-width="0.75"/></svg>
                    <span>{{ ceil(str_word_count(strip_tags($article->content)) / 200) }} min read</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 prose prose-lg prose-headings:font-serif prose-headings:text-jp-indigo prose-p:text-jp-indigo/75 prose-a:text-jp-gold hover:prose-a:text-jp-indigo prose-img:rounded-sm prose-strong:text-jp-indigo">
        {!! $article->content !!}
    </article>

    <!-- Related Articles -->
    @if($relatedArticles->isNotEmpty())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-32 pt-16 relative">
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-jp-indigo/10 to-transparent"></div>
            <div class="text-center mb-12">
                <svg width="40" height="12" viewBox="0 0 40 12" fill="none" class="mx-auto mb-4"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#C5A47E" stroke-width="1"/></svg>
                <h3 class="font-serif text-3xl text-jp-indigo">Continue Reading</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($relatedArticles as $related)
                    <a href="{{ route('public.news.show', $related->slug) }}" class="group block" x-data="{ shown: false }" x-intersect.once="shown = true">
                        <div class="relative overflow-hidden aspect-[4/3] mb-4 reveal bg-jp-mist shadow-wave" :class="shown ? 'active' : ''">
                            @if($related->getFirstMediaUrl('cover'))
                                <img src="{{ $related->getFirstMediaUrl('cover') }}" alt="{{ $related->title }}" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-cinematic ease-cinematic">
                            @endif
                        </div>
                        <div class="reveal reveal-delay-100" :class="shown ? 'active' : ''">
                            <h4 class="font-serif text-xl text-jp-indigo group-hover:text-jp-gold transition-colors duration-500 leading-snug">{{ $related->title }}</h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
    // Reading Progress Indicator
    document.addEventListener('scroll', function() {
        const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
        const clientHeight = document.documentElement.clientHeight;
        const scrolled = (scrollTop / (scrollHeight - clientHeight)) * 100;
        document.getElementById('reading-progress').style.width = scrolled + '%';
    });
</script>
@endsection
