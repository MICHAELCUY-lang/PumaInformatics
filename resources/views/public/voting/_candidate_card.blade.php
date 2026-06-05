{{--
    Candidate Card Partial
    Variables:
      $candidate   — Candidate model (with votes_count)
      $showResults — bool: display vote percentage bar
      $totalVotes  — int: total votes in session
      $selectable  — bool: show radio button selection state
      $voted       — bool: this is the candidate the user voted for
      $isWinner    — bool (optional): highlight as winner
--}}
@php $percentage = ($totalVotes > 0 && $showResults) ? round(($candidate->votes_count / $totalVotes) * 100) : 0; @endphp

<div class="group/card relative bg-white border transition-all duration-500
    {{ $selectable ? 'border-jp-indigo/10 peer-checked:border-jp-indigo peer-checked:shadow-wave' : 'border-jp-indigo/10' }}
    {{ isset($isWinner) && $isWinner ? 'border-l-4 border-l-jp-gold shadow-wave' : '' }}
    {{ $voted ? 'border-l-4 border-l-jp-indigo' : '' }}">

    {{-- Winner Badge --}}
    @if(isset($isWinner) && $isWinner)
        <div class="absolute top-4 right-4 flex items-center gap-2 bg-jp-gold text-white px-3 py-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Winner</span>
        </div>
    @endif

    {{-- Your vote badge --}}
    @if($voted)
        <div class="absolute top-4 right-4 flex items-center gap-2 bg-jp-indigo text-jp-cream px-3 py-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Your Vote</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-12 gap-0">

        {{-- Portrait --}}
        <div class="md:col-span-3 relative overflow-hidden bg-jp-mist" style="min-height: 240px;">
            @if($candidate->getFirstMediaUrl('portrait'))
                <img src="{{ $candidate->getFirstMediaUrl('portrait') }}" alt="{{ $candidate->name }}"
                     class="absolute inset-0 w-full h-full object-cover object-top filter grayscale {{ $selectable ? 'peer-checked:grayscale-0 group-hover/card:grayscale-0' : '' }} transition-all duration-700">
            @else
                <div class="absolute inset-0 flex items-center justify-center bg-jp-indigo/5">
                    <span class="font-serif text-7xl text-jp-indigo/15">{{ substr($candidate->name, 0, 1) }}</span>
                </div>
            @endif
        </div>

        {{-- Content --}}
        <div class="md:col-span-9 p-8 md:p-10 flex flex-col justify-between">
            <div>
                <h3 class="font-serif text-3xl text-jp-indigo mb-1
                    {{ $selectable ? 'group-hover/card:text-jp-gold peer-checked:text-jp-gold' : '' }}
                    transition-colors duration-500">
                    {{ $candidate->name }}
                </h3>

                @if($candidate->vision)
                    <div class="mt-4 mb-6">
                        <span class="text-[10px] uppercase tracking-[0.25em] text-jp-gold font-semibold mb-2 block">Vision</span>
                        <p class="text-jp-indigo/50 leading-relaxed">{{ Str::limit($candidate->vision, 300) }}</p>
                    </div>
                @endif

                @if($candidate->mission)
                    <div class="mb-6">
                        <span class="text-[10px] uppercase tracking-[0.25em] text-jp-gold font-semibold mb-2 block">Mission</span>
                        <p class="text-jp-indigo/50 leading-relaxed text-sm">{{ Str::limit($candidate->mission, 200) }}</p>
                    </div>
                @endif

                @if($candidate->social_links)
                    <div class="flex items-center gap-4 mt-4">
                        @foreach($candidate->social_links as $platform => $url)
                            @if($url)
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                   class="text-[10px] uppercase tracking-[0.2em] text-jp-indigo/30 hover:text-jp-gold transition-colors duration-500 font-semibold"
                                   onclick="event.stopPropagation()">
                                    {{ ucfirst($platform) }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Results Bar --}}
            @if($showResults && $totalVotes > 0)
                <div class="mt-8 pt-6 border-t border-jp-indigo/10">
                    <div class="flex justify-between items-baseline mb-2">
                        <span class="text-[10px] uppercase tracking-[0.2em] text-jp-indigo/30 font-semibold">Votes Received</span>
                        <div class="flex items-baseline gap-3">
                            <span class="font-serif text-3xl text-jp-indigo">{{ $percentage }}%</span>
                            <span class="text-sm text-jp-indigo/40">{{ $candidate->votes_count }} {{ Str::plural('vote', $candidate->votes_count) }}</span>
                        </div>
                    </div>
                    <div class="relative h-1.5 bg-jp-mist overflow-hidden">
                        <div class="absolute left-0 top-0 h-full {{ isset($isWinner) && $isWinner ? 'bg-jp-gold' : 'bg-jp-indigo' }} transition-all duration-1000 ease-out"
                             x-data="{ width: 0 }"
                             x-intersect.once="setTimeout(() => width = {{ $percentage }}, 300)"
                             :style="`width: ${width}%`">
                        </div>
                    </div>
                </div>
            @elseif($showResults)
                <div class="mt-8 pt-6 border-t border-jp-indigo/10">
                    <span class="text-[10px] uppercase tracking-[0.2em] text-jp-indigo/30 font-semibold">{{ $candidate->votes_count }} {{ Str::plural('vote', $candidate->votes_count) }} — 0%</span>
                </div>
            @endif

            {{-- Selection Indicator --}}
            @if($selectable)
                <div class="mt-6 flex items-center justify-between border-t border-jp-indigo/10 pt-4">
                    <span class="text-[10px] uppercase tracking-[0.2em] font-semibold text-jp-indigo/30 peer-checked:text-jp-indigo transition-colors">
                        Click to select
                    </span>
                    <div class="w-6 h-6 border-2 border-jp-indigo/20 rounded-full flex items-center justify-center peer-checked:border-jp-indigo peer-checked:bg-jp-indigo transition-all duration-300">
                        <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
