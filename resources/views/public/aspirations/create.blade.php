@extends('layouts.public')

@php
    /** @var \Illuminate\Support\Collection<int, \App\Models\AspirationCategory> $categories */
@endphp

@section('title', 'Submit an Aspiration')
@section('meta_description', 'Contribute your voice to the institution securely and transparently.')

@section('content')
<div class="min-h-screen">

    <!-- Header -->
    <section class="pt-24 sm:pt-32 pb-16 sm:pb-20 relative overflow-hidden bg-sapientia-ink border-b border-sapientia-primary/10">
        <div class="absolute inset-0 bg-topo opacity-[0.22] mix-blend-multiply"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" x-data="{ shown: false }" x-intersect.once="shown = true">
            <div class="flex justify-center mb-6 reveal" :class="shown ? 'active' : ''">
                <svg width="40" height="12" viewBox="0 0 40 12" fill="none"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#448AFF" stroke-width="1.5"/></svg>
            </div>
            <h1 class="font-serif text-4xl sm:text-5xl md:text-7xl text-sapientia-deep mb-6 reveal" :class="shown ? 'active' : ''">Aspirations</h1>
            <p class="text-sapientia-deep/70 max-w-2xl mx-auto text-base sm:text-lg reveal reveal-delay-100" :class="shown ? 'active' : ''">
                A secure, institutional channel for submitting feedback, proposals, and concerns. Your voice shapes our future.
            </p>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-sapientia-primary/25 to-transparent"></div>
    </section>

    <section class="py-24">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-12 p-8 bg-jp-indigo-deep text-jp-cream text-center shadow-wave relative overflow-hidden">
                    <div class="absolute inset-0 jp-seigaiha opacity-[0.04]"></div>
                    <div class="relative z-10">
                        <svg class="w-12 h-12 text-jp-gold mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="font-serif text-2xl mb-2">Submission Received</h3>
                        <p class="text-jp-cream/70 font-light">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('public.aspirations.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 md:p-12 shadow-wave border border-jp-indigo/5 relative overflow-hidden" x-data="{ isAnonymous: false, shown: false }" x-intersect.once="shown = true">
                @csrf
                <!-- Gold top accent -->
                <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-jp-gold via-jp-indigo to-jp-gold"></div>

                <div class="mb-12 border-b border-jp-indigo/10 pb-8 reveal" :class="shown ? 'active' : ''">
                    <h2 class="font-serif text-2xl text-jp-indigo mb-2">Submission Details</h2>
                    <p class="text-jp-indigo/40 text-sm">Please provide clear and constructive information.</p>
                </div>

                <!-- Category -->
                <div class="mb-8 reveal reveal-delay-100" :class="shown ? 'active' : ''">
                    <label for="aspiration_category_id" class="block text-[10px] uppercase tracking-[0.25em] text-jp-indigo font-semibold mb-3">Category *</label>
                    <select name="aspiration_category_id" id="aspiration_category_id" required class="w-full bg-jp-cream border-jp-indigo/15 focus:border-jp-gold focus:ring-0 text-jp-indigo py-4 px-4 transition-colors">
                        <option value="">Select an appropriate category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('aspiration_category_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Subject -->
                <div class="mb-8 reveal reveal-delay-100" :class="shown ? 'active' : ''">
                    <label for="subject" class="block text-[10px] uppercase tracking-[0.25em] text-jp-indigo font-semibold mb-3">Subject *</label>
                    <input type="text" name="subject" id="subject" required class="w-full bg-jp-cream border-jp-indigo/15 focus:border-jp-gold focus:ring-0 text-jp-indigo py-4 px-4 transition-colors" placeholder="Brief summary of your aspiration">
                    @error('subject') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Message -->
                <div class="mb-8 reveal reveal-delay-200" :class="shown ? 'active' : ''">
                    <label for="message" class="block text-[10px] uppercase tracking-[0.25em] text-jp-indigo font-semibold mb-3">Message *</label>
                    <textarea name="message" id="message" rows="6" required class="w-full bg-jp-cream border-jp-indigo/15 focus:border-jp-gold focus:ring-0 text-jp-indigo py-4 px-4 transition-colors" placeholder="Detailed description of your proposal, concern, or feedback..."></textarea>
                    @error('message') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Privacy Controls -->
                <div class="mb-12 bg-jp-cream p-6 border border-jp-indigo/10 reveal reveal-delay-200" :class="shown ? 'active' : ''">
                    <h3 class="font-serif text-lg text-jp-indigo mb-4">Privacy & Visibility</h3>
                    
                    <div class="space-y-4">
                        <label class="flex items-start gap-4 cursor-pointer group">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="is_anonymous" value="1" x-model="isAnonymous" class="w-5 h-5 border-jp-indigo/20 text-jp-indigo focus:ring-0 bg-white group-hover:border-jp-gold transition-colors">
                            </div>
                            <div>
                                <span class="block text-sm font-semibold text-jp-indigo mb-1">Submit Anonymously</span>
                                <span class="block text-sm text-jp-indigo/40">Your identity will not be recorded or associated with this submission. We will be unable to contact you for follow-up.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-4 cursor-pointer group">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="is_public" value="1" checked class="w-5 h-5 border-jp-indigo/20 text-jp-indigo focus:ring-0 bg-white group-hover:border-jp-gold transition-colors">
                            </div>
                            <div>
                                <span class="block text-sm font-semibold text-jp-indigo mb-1">Allow Public Display</span>
                                <span class="block text-sm text-jp-indigo/40">If selected, this aspiration may be displayed publicly on the platform (after moderation). Your name will only be shown if you do not submit anonymously.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Contact Info (Hidden if Anonymous) -->
                @guest
                    <div x-show="!isAnonymous" x-transition class="mb-12 grid grid-cols-1 md:grid-cols-2 gap-8 reveal reveal-delay-300" :class="shown ? 'active' : ''">
                        <div>
                            <label for="submitter_name" class="block text-[10px] uppercase tracking-[0.25em] text-jp-indigo font-semibold mb-3">Your Name</label>
                            <input type="text" name="submitter_name" id="submitter_name" class="w-full bg-jp-cream border-jp-indigo/15 focus:border-jp-gold focus:ring-0 text-jp-indigo py-4 px-4 transition-colors">
                            @error('submitter_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="submitter_email" class="block text-[10px] uppercase tracking-[0.25em] text-jp-indigo font-semibold mb-3">Your Email</label>
                            <input type="email" name="submitter_email" id="submitter_email" class="w-full bg-jp-cream border-jp-indigo/15 focus:border-jp-gold focus:ring-0 text-jp-indigo py-4 px-4 transition-colors">
                            @error('submitter_email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                @endguest

                <!-- Attachments -->
                <div class="mb-12 reveal reveal-delay-300" :class="shown ? 'active' : ''">
                    <label class="block text-[10px] uppercase tracking-[0.25em] text-jp-indigo font-semibold mb-3">Supporting Documents (Optional)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-jp-indigo/10 border-dashed bg-jp-cream hover:bg-white hover:border-jp-gold transition-colors cursor-pointer" onclick="document.getElementById('attachments').click()">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-10 w-10 text-jp-indigo/30" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-jp-indigo justify-center">
                                <span class="relative cursor-pointer font-medium text-jp-gold hover:text-jp-indigo">
                                    <span>Upload files</span>
                                    <input id="attachments" name="attachments[]" type="file" multiple class="sr-only">
                                </span>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-jp-indigo/30">PNG, JPG, PDF up to 10MB</p>
                        </div>
                    </div>
                    @error('attachments.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="text-center reveal reveal-delay-300" :class="shown ? 'active' : ''">
                    <button type="submit" class="inline-flex justify-center items-center px-10 py-4 bg-jp-indigo text-jp-cream text-[11px] font-semibold uppercase tracking-[0.25em] hover:bg-jp-gold transition-colors duration-500 w-full md:w-auto">
                        Submit Aspiration
                    </button>
                    <p class="mt-4 text-[10px] text-jp-indigo/30 tracking-[0.15em]">
                        Protected by rate-limiting and anti-abuse architecture.
                    </p>
                </div>
            </form>

        </div>
    </section>

</div>
@endsection
