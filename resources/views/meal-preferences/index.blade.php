<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meal Preferences') }}
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Meal Preferences</h2>
                        <p class="text-gray-600 mt-1">
                            @if(auth()->user()->hasRole(['admin', 'manager']))
                                Manage all user meal preferences
                            @else
                                Manage your meal preferences and opt-in for upcoming meals
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('meal-preferences.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Set Preferences
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Preferences Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @if(auth()->user()->hasRole(['admin', 'manager']))
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Special Requirements</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($preferences as $preference)
                                <tr class="hover:bg-gray-50">
                                    @if(auth()->user()->hasRole(['admin', 'manager']))
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $preference->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $preference->user->email }}</div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $preference->meal->name }}</div>
                                        @if($preference->meal->description)
                                            <div class="text-xs text-gray-500">{{ Str::limit($preference->meal->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $preference->meal->meal_date->format('M j, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $preference->meal->meal_time }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($preference->meal->type === 'breakfast') bg-yellow-100 text-yellow-800
                                            @elseif($preference->meal->type === 'lunch') bg-blue-100 text-blue-800
                                            @else bg-purple-100 text-purple-800 @endif">
                                            {{ ucfirst($preference->meal->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $preference->meal->mealCategory->name ?? 'General' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($preference->opted_in) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                            {{ $preference->opted_in ? 'Opted In' : 'Opted Out' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">
                                            {{ $preference->special_requirements ?: 'None' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('meal-preferences.show', $preference) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            @if(auth()->user()->hasRole(['admin', 'manager']) || auth()->id() === $preference->user_id)
                                                <a href="{{ route('meal-preferences.edit', $preference) }}" class="text-green-600 hover:text-green-900">Edit</a>
                                                <form action="{{ route('meal-preferences.destroy', $preference) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                                            onclick="return confirm('Are you sure you want to delete this preference?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->hasRole(['admin', 'manager']) ? '8' : '7' }}" class="px-6 py-4 text-center text-gray-500">
                                        No meal preferences found. 
                                        <a href="{{ route('meal-preferences.create') }}" class="text-blue-600 hover:text-blue-900">Set your first preference</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($preferences->hasPages())
                    <div class="mt-6">
                        {{ $preferences->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>