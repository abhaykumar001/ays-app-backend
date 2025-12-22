<x-app-layout>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome section -->
            <div class="mb-10 bg-gray-50 dark:bg-gray-900 rounded-2xl shadow flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Welcome back, {{ $user->name }} 👋
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Here’s a quick look at your platform activity.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                @can('view_users')
                    <!-- Users -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6 flex flex-col justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Users</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $users }}</h3>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <a href="{{ route('user.index') }}"
                                class="text-sm text-purple-600 hover:text-purple-500 dark:text-purple-400">
                                View →
                            </a>
                            <i class="bi bi-people w-6 h-6 text-purple-500"></i>
                        </div>
                    </div>
                @endcan
            </div>


        </div>
    </div>
</x-app-layout>
