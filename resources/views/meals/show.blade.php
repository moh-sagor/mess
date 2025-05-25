<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meal Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $meal->name }}</h2>
                            <p class="text-gray-600 mt-1">{{ ucfirst($meal->type) }} meal details</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('meals.index') }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Meals
                            </a>
                            @can('update', $meal)
                                <a href="{{ route('meals.edit', $meal) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Edit Meal
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meal Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Basic Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Meal Name:</span>
                                    <span class="text-lg font-semibold text-gray-900 ml-2">{{ $meal->name }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Type:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                        @if($meal->type === 'breakfast') bg-yellow-100 text-yellow-800
                                        @elseif($meal->type === 'lunch') bg-green-100 text-green-800
                                        @elseif($meal->type === 'dinner') bg-blue-100 text-blue-800
                                        @else bg-purple-100 text-purple-800 @endif">
                                        {{ ucfirst($meal->type) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Category:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $meal->category->name }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Status:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                        {{ $meal->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $meal->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule & Cost -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Schedule & Cost</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Date:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $meal->meal_date->format('l, F j, Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Time:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ \Carbon\Carbon::parse($meal->meal_time)->format('g:i A') }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Estimated Cost:</span>
                                    <span class="text-lg font-bold text-green-600 ml-2">${{ number_format($meal->estimated_cost, 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Days Until:</span>
                                    <span class="text-sm text-gray-600 ml-2">{{ $meal->meal_date->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Meal Preferences Stats -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Participation</h3>
                            <div class="space-y-3">
                                @php
                                    $totalPreferences = $meal->preferences()->count();
                                    $confirmedPreferences = $meal->preferences()->where('status', 'confirmed')->count();
                                @endphp
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Total Preferences:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $totalPreferences }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Confirmed:</span>
                                    <span class="text-sm text-green-600 font-semibold ml-2">{{ $confirmedPreferences }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Pending:</span>
                                    <span class="text-sm text-yellow-600 font-semibold ml-2">{{ $totalPreferences - $confirmedPreferences }}</span>
                                </div>
                                @if($totalPreferences > 0)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Estimated Total Cost:</span>
                                        <span class="text-lg font-bold text-blue-600 ml-2">${{ number_format($meal->estimated_cost * $confirmedPreferences, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Record Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Record Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Created:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $meal->created_at->format('M j, Y \a\t g:i A') }}</span>
                                </div>
                                @if($meal->updated_at != $meal->created_at)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Last Updated:</span>
                                        <span class="text-sm text-gray-900 ml-2">{{ $meal->updated_at->format('M j, Y \a\t g:i A') }}</span>
                                    </div>
                                @endif
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Meal ID:</span>
                                    <span class="text-sm text-gray-600 ml-2">#{{ $meal->id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($meal->description)
                        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $meal->description }}</p>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="mt-6 flex justify-end space-x-3">
                        @can('update', $meal)
                            <a href="{{ route('meals.edit', $meal) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit Meal
                            </a>
                        @endcan
                        @can('delete', $meal)
                            <form action="{{ route('meals.destroy', $meal) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="return confirm('Are you sure you want to delete this meal? This action cannot be undone.')">
                                    Delete Meal
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>