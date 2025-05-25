<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Meal Preference') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Edit Meal Preference</h2>
                            <p class="text-gray-600 mt-1">Update your meal preference settings</p>
                        </div>
                        <a href="{{ route('meal-preferences.show', $mealPreference) }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Meal Information (Read-only) -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Meal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <h4 class="text-base font-medium text-gray-900">{{ $mealPreference->meal->name }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($mealPreference->meal->type === 'breakfast') bg-yellow-100 text-yellow-800
                                        @elseif($mealPreference->meal->type === 'lunch') bg-blue-100 text-blue-800
                                        @else bg-purple-100 text-purple-800 @endif">
                                        {{ ucfirst($mealPreference->meal->type) }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $mealPreference->meal->mealCategory->name }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium">Date:</span> {{ $mealPreference->meal->meal_date->format('l, F j, Y') }} at {{ $mealPreference->meal->meal_time }}
                                </div>
                                @if($mealPreference->meal->description)
                                    <p class="text-sm text-gray-600 mt-2">{{ $mealPreference->meal->description }}</p>
                                @endif
                            </div>
                            @if($mealPreference->meal->estimated_cost)
                                <div>
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium">Estimated Cost:</span> ${{ number_format($mealPreference->meal->estimated_cost, 2) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <form action="{{ route('meal-preferences.update', $mealPreference) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Participation Option -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Participation</label>
                            <div class="flex items-center">
                                <input type="checkbox" name="opted_in" id="opted_in" value="1" 
                                       {{ $mealPreference->opted_in ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <label for="opted_in" class="ml-2 block text-sm text-gray-900">
                                    I want to participate in this meal
                                </label>
                            </div>
                            @error('opted_in')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Special Requirements -->
                        <div class="mb-6">
                            <label for="special_requirements" class="block text-sm font-medium text-gray-700 mb-2">
                                Special Requirements (Optional)
                            </label>
                            <textarea name="special_requirements" id="special_requirements" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Any dietary restrictions, allergies, or special requests...">{{ old('special_requirements', $mealPreference->special_requirements) }}</textarea>
                            @error('special_requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Maximum 500 characters</p>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('meal-preferences.show', $mealPreference) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Preference
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>