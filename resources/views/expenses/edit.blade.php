@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Edit Expense</h2>
                        <p class="text-gray-600">Update expense information</p>
                    </div>
                    <a href="{{ route('expenses.show', $expense) }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('expenses.update', $expense) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Title *
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title', $expense->title) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                                   placeholder="Enter expense title"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Amount ($) *
                            </label>
                            <input type="number" 
                                   name="amount" 
                                   id="amount" 
                                   value="{{ old('amount', $expense->amount) }}"
                                   step="0.01" 
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('amount') border-red-500 @enderror"
                                   placeholder="0.00"
                                   required>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Category *
                            </label>
                            <select name="category" 
                                    id="category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror"
                                    required>
                                <option value="">Select Category</option>
                                <option value="food" {{ old('category', $expense->category) === 'food' ? 'selected' : '' }}>Food</option>
                                <option value="utilities" {{ old('category', $expense->category) === 'utilities' ? 'selected' : '' }}>Utilities</option>
                                <option value="maintenance" {{ old('category', $expense->category) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="supplies" {{ old('category', $expense->category) === 'supplies' ? 'selected' : '' }}>Supplies</option>
                                <option value="transportation" {{ old('category', $expense->category) === 'transportation' ? 'selected' : '' }}>Transportation</option>
                                <option value="miscellaneous" {{ old('category', $expense->category) === 'miscellaneous' ? 'selected' : '' }}>Miscellaneous</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Expense Date -->
                        <div>
                            <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Expense Date *
                            </label>
                            <input type="date" 
                                   name="expense_date" 
                                   id="expense_date" 
                                   value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('expense_date') border-red-500 @enderror"
                                   required>
                            @error('expense_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Enter expense description (optional)">{{ old('description', $expense->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bazaar Record (Optional) -->
                    <div>
                        <label for="bazaar_record_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Related Bazaar Record (Optional)
                        </label>
                        <select name="bazaar_record_id" 
                                id="bazaar_record_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bazaar_record_id') border-red-500 @enderror">
                            <option value="">No related bazaar record</option>
                            @foreach($bazaarRecords as $record)
                                <option value="{{ $record->id }}" 
                                        {{ old('bazaar_record_id', $expense->bazaar_record_id) == $record->id ? 'selected' : '' }}>
                                    {{ $record->item_name }} - ${{ number_format($record->total_cost, 2) }} ({{ $record->purchase_date->format('M j, Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('bazaar_record_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Information -->
                    @if($expense->status !== 'pending')
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        Note: This expense has been {{ $expense->status }}
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Editing this expense will reset its status to "pending" and require re-approval.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('expenses.show', $expense) }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            Update Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection