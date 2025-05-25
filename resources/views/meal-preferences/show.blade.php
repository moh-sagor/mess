<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meal Preference Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Meal Preference Details</h2>
                            <p class="text-gray-600 mt-1">View meal preference information</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('meal-preferences.index') }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Preferences
                            </a>
                            @if(auth()->user()->hasRole(['admin', 'manager']) || auth()->id() === $mealPreference->user_id)
                                <a href="{{ route('meal-preferences.edit', $mealPreference) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Edit Preference
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preference Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Information -->
                        @if(auth()->user()->hasRole(['admin', 'manager']))
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">User Information</h3>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Name:</span>
                                        <span class="text-sm text-gray-900 ml-2">{{ $mealPreference->user->name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Email:</span>
                                        <span class="text-sm text-gray-900 ml-2">{{ $mealPreference->user->email }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Role:</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                            {{ ucfirst($mealPreference->user->getRoleNames()->first()) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Meal Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Meal Information</h3>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Meal Name:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $mealPreference->meal->name }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Type:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                        @if($mealPreference->meal->type === 'breakfast') bg-yellow-100 text-yellow-800
                                        @elseif($mealPreference->meal->type === 'lunch') bg-blue-100 text-blue-800
                                        @else bg-purple-100 text-purple-800 @endif">
                                        {{ ucfirst($mealPreference->meal->type) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Category:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                        {{ $mealPreference->meal->mealCategory->name }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Date & Time:</span>
                                    <span class="text-sm text-gray-900 ml-2">
                                        {{ $mealPreference->meal->meal_date->format('l, F j, Y') }} at {{ $mealPreference->meal->meal_time }}
                                    </span>
                                </div>
                                @if($mealPreference->meal->estimated_cost)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Estimated Cost:</span>
                                        <span class="text-sm text-gray-900 ml-2">${{ number_format($mealPreference->meal->estimated_cost, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Preference Status -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Preference Status</h3>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Participation:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                        @if($mealPreference->opted_in) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                        {{ $mealPreference->opted_in ? 'Opted In' : 'Opted Out' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Submitted:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $mealPreference->created_at->format('M j, Y \a\t g:i A') }}</span>
                                </div>
                                @if($mealPreference->updated_at != $mealPreference->created_at)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Last Updated:</span>
                                        <span class="text-sm text-gray-900 ml-2">{{ $mealPreference->updated_at->format('M j, Y \a\t g:i A') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Special Requirements -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Special Requirements</h3>
                            @if($mealPreference->special_requirements)
                                <p class="text-sm text-gray-900">{{ $mealPreference->special_requirements }}</p>
                            @else
                                <p class="text-sm text-gray-500 italic">No special requirements specified</p>
                            @endif
                        </div>
                    </div>

                    <!-- Meal Description -->
                    @if($mealPreference->meal->description)
                        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Meal Description</h3>
                            <p class="text-sm text-gray-900">{{ $mealPreference->meal->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>