<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Bazaar Purchase') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Add Bazaar Purchase</h2>
                            <p class="text-gray-600 mt-1">Record a new market purchase</p>
                        </div>
                        <a href="{{ route('bazaar-records.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Records
                        </a>
                    </div>
                </div>
            </div>

            <!-- Create Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('bazaar-records.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Item Name -->
                            <div>
                                <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Item Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="item_name" id="item_name" value="{{ old('item_name') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="e.g., Rice, Vegetables, Oil" required>
                                @error('item_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Quantity <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" step="0.01" min="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="e.g., 5, 2.5" required>
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit -->
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                    Unit <span class="text-red-500">*</span>
                                </label>
                                <select name="unit" id="unit" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Select Unit</option>
                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                    <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                                    <option value="lbs" {{ old('unit') == 'lbs' ? 'selected' : '' }}>Pounds (lbs)</option>
                                    <option value="liters" {{ old('unit') == 'liters' ? 'selected' : '' }}>Liters</option>
                                    <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Milliliters (ml)</option>
                                    <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>Pieces</option>
                                    <option value="packets" {{ old('unit') == 'packets' ? 'selected' : '' }}>Packets</option>
                                    <option value="boxes" {{ old('unit') == 'boxes' ? 'selected' : '' }}>Boxes</option>
                                    <option value="bags" {{ old('unit') == 'bags' ? 'selected' : '' }}>Bags</option>
                                    <option value="bottles" {{ old('unit') == 'bottles' ? 'selected' : '' }}>Bottles</option>
                                </select>
                                @error('unit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cost -->
                            <div>
                                <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">
                                    Total Cost ($) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="cost" id="cost" value="{{ old('cost') }}" step="0.01" min="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="e.g., 25.50" required>
                                @error('cost')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Purchase Date -->
                            <div>
                                <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Purchase Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('purchase_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Vendor -->
                            <div>
                                <label for="vendor" class="block text-sm font-medium text-gray-700 mb-2">
                                    Vendor/Shop Name
                                </label>
                                <input type="text" name="vendor" id="vendor" value="{{ old('vendor') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="e.g., Local Market, ABC Store">
                                @error('vendor')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Any additional notes about this purchase...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Maximum 500 characters</p>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('bazaar-records.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add Purchase
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Calculate unit price when quantity or cost changes
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const costInput = document.getElementById('cost');
            const unitSelect = document.getElementById('unit');
            
            function updateUnitPrice() {
                const quantity = parseFloat(quantityInput.value);
                const cost = parseFloat(costInput.value);
                const unit = unitSelect.value;
                
                if (quantity > 0 && cost > 0 && unit) {
                    const unitPrice = (cost / quantity).toFixed(2);
                    // You could display this somewhere if needed
                    console.log(`Unit price: $${unitPrice}/${unit}`);
                }
            }
            
            quantityInput.addEventListener('input', updateUnitPrice);
            costInput.addEventListener('input', updateUnitPrice);
            unitSelect.addEventListener('change', updateUnitPrice);
        });
    </script>
</x-app-layout>