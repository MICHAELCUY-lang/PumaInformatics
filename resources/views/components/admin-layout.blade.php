<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PUMA IT') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-museum-paper text-museum-dark selection:bg-museum-dark selection:text-white" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex flex-col md:flex-row">
            <!-- Sidebar -->
            <aside class="w-full md:w-64 bg-white border-r border-gray-100/50 flex-shrink-0 flex flex-col py-8 transition-transform duration-300 transform absolute z-20 h-full md:relative md:translate-x-0"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                <div class="flex items-center justify-between px-8 mb-12">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-museum-black text-white flex items-center justify-center font-serif text-xl italic leading-none">P</div>
                        <span class="text-xl font-serif font-semibold tracking-wide">PUMA IT</span>
                    </a>
                    <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <nav class="flex-1 overflow-y-auto pb-4">
                    <ul class="space-y-6 px-4">
                        <!-- Dashboard -->
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.dashboard') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                Dashboard
                            </a>
                        </li>

                        <!-- Content -->
                        <li>
                            <h4 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Content</h4>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('admin.articles.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.articles.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Newsroom
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.events.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.events.*', 'admin.event-categories.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Events
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.projects.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.projects.*', 'admin.project-categories.*', 'admin.technologies.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Projects
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.cabinets.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.cabinets.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Cabinets
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.cabinet-members.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.cabinet-members.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Cabinet Members
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.cabinet-departments.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.cabinet-departments.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Departments
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.partners.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.partners.*', 'admin.partner-categories.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Partners
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Community -->
                        <li>
                            <h4 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Community</h4>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('admin.aspirations.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.aspirations.*', 'admin.aspiration-categories.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Aspirations
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.voting-sessions.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.voting-sessions.*', 'admin.candidates.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Voting
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Governance -->
                        <li>
                            <h4 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Governance</h4>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.users.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Users
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.roles.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.roles.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Roles
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.activity-logs.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.activity-logs.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Activity Ledger
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- System -->
                        <li>
                            <h4 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">System</h4>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('admin.media.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.media.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Media Library
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.navigations.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.navigations.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Navigation
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.cache.index') }}" class="block px-4 py-2.5 rounded-none text-sm font-medium transition-colors hover:text-museum-black {{ request()->routeIs('admin.cache.*') ? 'text-museum-black border-l-2 border-museum-black bg-museum-light/50' : 'text-museum-gray hover:bg-museum-light/50 border-l-2 border-transparent' }}">
                                        Cache Management
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-10 bg-black opacity-50 md:hidden"></div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden relative z-0">
                <!-- Topbar -->
                <header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-100/50">
                    <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none md:hidden">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div class="flex items-center ml-auto">
                        <span class="text-sm text-gray-500">{{ Auth::user()?->name ?? 'Admin User' }}</span>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                    <div class="max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
