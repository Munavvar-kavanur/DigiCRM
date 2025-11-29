<x-app-layout>
    <div class="space-y-8">
        
        <!-- Header Section -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-900 dark:to-purple-900 shadow-xl">
            <div class="absolute inset-0 bg-white/10 dark:bg-black/10 backdrop-blur-[1px]"></div>
            <div class="relative p-8 md:p-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-white">
                    <h2 class="text-3xl md:text-4xl font-bold tracking-tight">
                        {{ __('Branches') }}
                    </h2>
                    <p class="mt-2 text-indigo-100 text-lg">
                        Manage your organization's branch offices and locations.
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('branches.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 hover:bg-indigo-50 rounded-xl font-bold text-sm shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Branch
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Branches -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Branches</p>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $branches->total() }}</h3>
                    </div>
                    <div class="p-3.5 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        Active
                    </span>
                    <span class="text-gray-400 mx-2">•</span>
                    <span class="text-gray-500 dark:text-gray-400">Locations</span>
                </div>
            </div>
        </div>

        <!-- Filters & Content -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                <form action="{{ route('branches.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 justify-between">
                    <!-- Search -->
                    <div class="relative w-full md:w-96">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl leading-5 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent sm:text-sm transition-all" 
                            placeholder="Search by name, code, or email...">
                    </div>

                    @if(request('search'))
                        <a href="{{ route('branches.index') }}" class="inline-flex items-center px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Clear Filters
                        </a>
                    @endif
                </form>
            </div>

            @if (session('success'))
                <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 m-6 mb-0 rounded-r-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Branch Details</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact Info</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Address</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Currency</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($branches as $branch)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-sm shadow-sm">
                                                {{ substr($branch->name, 0, 2) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $branch->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                Code: <span class="font-mono bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded text-gray-600 dark:text-gray-300">{{ $branch->code }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1.5">
                                        @if($branch->email)
                                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                {{ $branch->email }}
                                            </div>
                                        @endif
                                        @if($branch->phone)
                                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                {{ $branch->phone }}
                                            </div>
                                        @endif
                                        @if(!$branch->email && !$branch->phone)
                                            <span class="text-sm text-gray-400 italic">No contact info</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate" title="{{ $branch->address }}">
                                        {{ $branch->address ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                        {{ $branch->currency ?? '$' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('branches.edit', $branch) }}" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-all duration-200" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <button type="button" @click="$dispatch('open-delete-modal', { actionUrl: '{{ route('branches.destroy', $branch) }}' })" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-all duration-200" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-full mb-4">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No branches found</h3>
                                        <p class="text-sm mb-6">Get started by adding your first branch.</p>
                                        <a href="{{ route('branches.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-sm transition-colors shadow-md">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Add Branch
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($branches->hasPages())
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $branches->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
