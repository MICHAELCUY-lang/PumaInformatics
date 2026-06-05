<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500">Welcome to the PUMA IT Control Panel.</p>
        </div>
        <div>
            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">System Online</span>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Total Users</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $analytics['total_users'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Total Events</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $analytics['total_events'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Total News</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $analytics['total_news'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Aspirations</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $analytics['pending_aspirations'] }}</p>
        </div>
    </div>

    <!-- Future: Add recent activity log table here -->
</x-admin-layout>
