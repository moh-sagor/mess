<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Set Meal Preferences') }}
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Set Meal Preferences</h2>
                        <p class="text-gray-600 mt-1">Choose your preferences for upcoming meals</p>
                    </div>
                    <a href="{{ route('meal-preferences.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to Preferences
                    </a>
                </div>
            </div>
        </div>

        <!-- Available Meals -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Available Meals</h3>
                
                @if($meals->count() > 0)
                    <div class="space-y-6">
                        @foreach($meals as $meal)
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <form action="{{ route('meal-preferences.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="meal_id" value="{{ $meal->id }}">
                                    
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h4 class="text-lg font-medium text-gray-900">{{ $meal->name }}</h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($meal->type === 'breakfast') bg-yellow-100 text-yellow-800
                                                    @elseif($meal->type === 'lunch') bg-blue-100 text-blue-800
                                                    @else bg-purple-100 text-purple-800 @endif">
                                                    {{ ucfirst($meal->type) }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $meal->mealCategory->name }}
                                                </span>
                                            </div>
                                            
                                            <div class="text-sm text-gray-600 mb-2">
                                                <span class="font-medium">Date:</span> {{ $meal->meal_date->format('l, F j, Y') }} at {{ $meal->meal_time }}
                                            </div>
                                            
                                            @if($meal->description)
                                                <p class="text-sm text-gray-600 mb-3">{{ $meal->description }}</p>
                                            @endif
                                            
                                            @if($meal->estimated_cost)
                                                <div class="text-sm text-gray-600">
                                                    <span class="font-medium">Estimated Cost:</span> ${{ number_format($meal->estimated_cost, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Preference Options -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <!-- Opt In Checkbox -->
                                        <div>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="opted_in" id="opted_in_{{ $meal->id }}" value="1" 
                                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <label for="opted_in_{{ $meal->id }}" class="ml-2 block text-sm text-gray-900">
                                                    I want to participate in this meal
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Special Requirements -->
                                    <div class="mb-4">
                                        <label for="special_requirements_{{ $meal->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                            Special Requirements (Optional)
                                        </label>
                                        <textarea name="special_requirements" id="special_requirements_{{ $meal->id }}" rows="2"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                  placeholder="Any dietary restrictions, allergies, or special requests..."></textarea>
                                        @error('special_requirements')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="flex justify-end">
                                        <button type="submit" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Save Preference
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-500 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Upcoming Meals</h3>
                        <p class="text-gray-500 mb-4">There are no meals available for preference setting at the moment.</p>
                        @if(auth()->user()->hasRole(['admin', 'manager']))
                            <a href="{{ route('meals.create') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create New Meal
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>