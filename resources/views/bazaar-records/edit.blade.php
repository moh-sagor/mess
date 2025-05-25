<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Bazaar Record') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Edit Bazaar Record</h2>
                            <p class="text-gray-600 mt-1">Update purchase information</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('bazaar-records.index') }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Records
                            </a>
                            <a href="{{ route('bazaar-records.show', $bazaarRecord) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                View Record
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('bazaar-records.update', $bazaarRecord) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Item Name -->
                            <div>
                                <label for="item_name" class="block text-sm font-medium text-gray-700">Item Name</label>
                                <input type="text" 
                                       name="item_name" 
                                       id="item_name" 
                                       value="{{ old('item_name', $bazaarRecord->item_name) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       required>
                                @error('item_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity" 
                                       value="{{ old('quantity', $bazaarRecord->quantity) }}"
                                       step="0.01" 
                                       min="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       required>
                                @error('quantity')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit -->
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                                <select name="unit" 
                                        id="unit" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required>
                                    <option value="">Select Unit</option>
                                    <option value="kg" {{ old('unit', $bazaarRecord->unit) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                    <option value="g" {{ old('unit', $bazaarRecord->unit) == 'g' ? 'selected' : '' }}>Gram (g)</option>
                                    <option value="lbs" {{ old('unit', $bazaarRecord->unit) == 'lbs' ? 'selected' : '' }}>Pounds (lbs)</option>
                                    <option value="pieces" {{ old('unit', $bazaarRecord->unit) == 'pieces' ? 'selected' : '' }}>Pieces</option>
                                    <option value="liters" {{ old('unit', $bazaarRecord->unit) == 'liters' ? 'selected' : '' }}>Liters</option>
                                    <option value="ml" {{ old('unit', $bazaarRecord->unit) == 'ml' ? 'selected' : '' }}>Milliliters (ml)</option>
                                    <option value="packets" {{ old('unit', $bazaarRecord->unit) == 'packets' ? 'selected' : '' }}>Packets</option>
                                    <option value="boxes" {{ old('unit', $bazaarRecord->unit) == 'boxes' ? 'selected' : '' }}>Boxes</option>
                                </select>
                                @error('unit')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Total Cost -->
                            <div>
                                <label for="cost" class="block text-sm font-medium text-gray-700">Total Cost ($)</label>
                                <input type="number" 
                                       name="cost" 
                                       id="cost" 
                                       value="{{ old('cost', $bazaarRecord->total_cost) }}"
                                       step="0.01" 
                                       min="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       required>
                                @error('cost')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Purchase Date -->
                            <div>
                                <label for="purchase_date" class="block text-sm font-medium text-gray-700">Purchase Date</label>
                                <input type="date" 
                                       name="purchase_date" 
                                       id="purchase_date" 
                                       value="{{ old('purchase_date', $bazaarRecord->purchase_date->format('Y-m-d')) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       required>
                                @error('purchase_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Vendor -->
                            <div>
                                <label for="vendor" class="block text-sm font-medium text-gray-700">Vendor (Optional)</label>
                                <input type="text" 
                                       name="vendor" 
                                       id="vendor" 
                                       value="{{ old('vendor', $bazaarRecord->vendor_name) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="e.g., Local Market, Grocery Store">
                                @error('vendor')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                      placeholder="Any additional notes about this purchase...">{{ old('notes', $bazaarRecord->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cost Calculation Display -->
                        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Cost Calculation</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Current Quantity:</span>
                                    <span class="text-gray-900 ml-2">{{ $bazaarRecord->quantity }} {{ $bazaarRecord->unit }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Current Unit Price:</span>
                                    <span class="text-gray-900 ml-2">${{ number_format($bazaarRecord->unit_price, 2) }}/{{ $bazaarRecord->unit }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Current Total:</span>
                                    <span class="text-green-600 font-semibold ml-2">${{ number_format($bazaarRecord->total_cost, 2) }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                <strong>Note:</strong> Unit price will be automatically calculated based on quantity and total cost.
                            </p>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('bazaar-records.show', $bazaarRecord) }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-calculate unit price when quantity or cost changes
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const costInput = document.getElementById('cost');
            
            function updateCalculation() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const totalCost = parseFloat(costInput.value) || 0;
                const unitPrice = quantity > 0 ? (totalCost / quantity) : 0;
                
                // You could add a display element here to show live calculation
                console.log('Unit Price:', unitPrice.toFixed(2));
            }
            
            quantityInput.addEventListener('input', updateCalculation);
            costInput.addEventListener('input', updateCalculation);
        });
    </script>
</x-app-layout>