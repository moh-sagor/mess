<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bazaar Record Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Bazaar Record Details</h2>
                            <p class="text-gray-600 mt-1">View purchase information</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('bazaar-records.index') }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Records
                            </a>
                            <a href="{{ route('bazaar-records.edit', $bazaarRecord) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit Record
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Record Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Item Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Item Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Item Name:</span>
                                    <span class="text-lg font-semibold text-gray-900 ml-2">{{ $bazaarRecord->item_name }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Quantity:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $bazaarRecord->quantity }} {{ $bazaarRecord->unit }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Unit Price:</span>
                                    <span class="text-sm text-gray-900 ml-2">${{ number_format($bazaarRecord->unit_price, 2) }}/{{ $bazaarRecord->unit }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Total Cost:</span>
                                    <span class="text-lg font-bold text-green-600 ml-2">${{ number_format($bazaarRecord->total_cost, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Purchase Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Purchase Date:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $bazaarRecord->purchase_date->format('l, F j, Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Time Ago:</span>
                                    <span class="text-sm text-gray-600 ml-2">{{ $bazaarRecord->purchase_date->diffForHumans() }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Vendor:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $bazaarRecord->vendor_name ?: 'Not specified' }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Added By:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $bazaarRecord->creator->name }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Record Metadata -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Record Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Created:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $bazaarRecord->created_at->format('M j, Y \a\t g:i A') }}</span>
                                </div>
                                @if($bazaarRecord->updated_at != $bazaarRecord->created_at)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Last Updated:</span>
                                        <span class="text-sm text-gray-900 ml-2">{{ $bazaarRecord->updated_at->format('M j, Y \a\t g:i A') }}</span>
                                    </div>
                                @endif
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Record ID:</span>
                                    <span class="text-sm text-gray-600 ml-2">#{{ $bazaarRecord->id }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Cost Analysis -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Cost Analysis</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Quantity:</span>
                                    <span class="text-sm text-gray-900">{{ $bazaarRecord->quantity }} {{ $bazaarRecord->unit }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Unit Price:</span>
                                    <span class="text-sm text-gray-900">${{ number_format($bazaarRecord->unit_price, 2) }}</span>
                                </div>
                                <hr class="border-gray-300">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-semibold text-gray-700">Total Cost:</span>
                                    <span class="text-lg font-bold text-green-600">${{ number_format($bazaarRecord->total_cost, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    @if($bazaarRecord->notes)
                        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Notes</h3>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $bazaarRecord->notes }}</p>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('bazaar-records.edit', $bazaarRecord) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Record
                        </a>
                        <form action="{{ route('bazaar-records.destroy', $bazaarRecord) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="return confirm('Are you sure you want to delete this record? This action cannot be undone.')">
                                Delete Record
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>