<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Meal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Edit Meal</h2>
                            <p class="text-gray-600 mt-1">Update meal information and schedule</p>
                        </div>
                        <a href="{{ route('meals.show', $meal) }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                    </div>

                    <form action="{{ route('meals.update', $meal) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Meal Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Meal Name</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $meal->name) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="e.g., Healthy Vegetarian Breakfast"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Meal Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Meal Type</label>
                                <select name="type" 
                                        id="type" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required>
                                    <option value="">Select meal type</option>
                                    <option value="breakfast" {{ old('type', $meal->type) === 'breakfast' ? 'selected' : '' }}>Breakfast</option>
                                    <option value="lunch" {{ old('type', $meal->type) === 'lunch' ? 'selected' : '' }}>Lunch</option>
                                    <option value="dinner" {{ old('type', $meal->type) === 'dinner' ? 'selected' : '' }}>Dinner</option>
                                    <option value="snack" {{ old('type', $meal->type) === 'snack' ? 'selected' : '' }}>Snack</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category_id" 
                                        id="category_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required>
                                    <option value="">Select category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $meal->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Estimated Cost -->
                            <div>
                                <label for="estimated_cost" class="block text-sm font-medium text-gray-700">Estimated Cost per Person ($)</label>
                                <input type="number" 
                                       name="estimated_cost" 
                                       id="estimated_cost" 
                                       value="{{ old('estimated_cost', $meal->estimated_cost) }}"
                                       step="0.01" 
                                       min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="0.00"
                                       required>
                                @error('estimated_cost')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Meal Date -->
                            <div>
                                <label for="meal_date" class="block text-sm font-medium text-gray-700">Meal Date</label>
                                <input type="date" 
                                       name="meal_date" 
                                       id="meal_date" 
                                       value="{{ old('meal_date', $meal->meal_date->format('Y-m-d')) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       required>
                                @error('meal_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Meal Time -->
                            <div>
                                <label for="meal_time" class="block text-sm font-medium text-gray-700">Meal Time</label>
                                <input type="time" 
                                       name="meal_time" 
                                       id="meal_time" 
                                       value="{{ old('meal_time', $meal->meal_time) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       required>
                                @error('meal_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                      placeholder="Describe the meal, ingredients, or special notes...">{{ old('description', $meal->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="mt-6">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1"
                                       {{ old('is_active', $meal->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Active (meal is available for preferences)
                                </label>
                            </div>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Cost Calculation -->
                        @if($meal->preferences()->where('status', 'confirmed')->count() > 0)
                            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-blue-900 mb-2">Current Cost Analysis</h4>
                                <div class="text-sm text-blue-700">
                                    <p>Confirmed Participants: {{ $meal->preferences()->where('status', 'confirmed')->count() }}</p>
                                    <p>Current Total Cost: ${{ number_format($meal->estimated_cost * $meal->preferences()->where('status', 'confirmed')->count(), 2) }}</p>
                                    <p class="text-xs text-blue-600 mt-1">Note: Updating the cost will affect future calculations</p>
                                </div>
                            </div>
                        @endif

                        <!-- Submit Buttons -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('meals.show', $meal) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Meal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>