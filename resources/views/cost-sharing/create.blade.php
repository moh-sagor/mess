@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Calculate Cost Sharing</h2>
                        <p class="text-gray-600">Calculate cost sharing for a specific period</p>
                    </div>
                    <a href="{{ route('cost-sharing.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('cost-sharing.calculate') }}" class="space-y-6">
                    @csrf

                    <!-- Period Selection -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Calculation Period</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="period_start" class="block text-sm font-medium text-gray-700">
                                    Period Start Date *
                                </label>
                                <input type="date" name="period_start" id="period_start" 
                                       value="{{ old('period_start', $startDate) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('period_start')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="period_end" class="block text-sm font-medium text-gray-700">
                                    Period End Date *
                                </label>
                                <input type="date" name="period_end" id="period_end" 
                                       value="{{ old('period_end', $endDate) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('period_end')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Sharing Method -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Cost Sharing Method</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="proportional" name="sharing_method" type="radio" value="proportional" 
                                           {{ old('sharing_method', 'proportional') === 'proportional' ? 'checked' : '' }}
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="proportional" class="font-medium text-gray-700">Proportional (Recommended)</label>
                                    <p class="text-gray-500">
                                        Food costs are split based on meal participation. Other expenses are split equally among all users.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="equal" name="sharing_method" type="radio" value="equal" 
                                           {{ old('sharing_method') === 'equal' ? 'checked' : '' }}
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="equal" class="font-medium text-gray-700">Equal Split</label>
                                    <p class="text-gray-500">
                                        All expenses (food and others) are split equally among all users, regardless of meal participation.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @error('sharing_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- User Selection -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Include Users</h3>
                        <p class="text-sm text-gray-600 mb-4">Select users to include in the cost sharing calculation. All users are included by default.</p>
                        
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            <div class="flex items-center mb-4">
                                <input type="checkbox" id="select_all" 
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <label for="select_all" class="ml-2 text-sm font-medium text-gray-700">
                                    Select All Users
                                </label>
                            </div>
                            
                            @foreach($users as $user)
                                <div class="flex items-center">
                                    <input type="checkbox" name="include_users[]" value="{{ $user->id }}" 
                                           id="user_{{ $user->id }}" checked
                                           class="user-checkbox focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    <label for="user_{{ $user->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $user->name }} ({{ $user->email }})
                                        <span class="text-gray-500">- {{ ucfirst($user->getRoleNames()->first()) }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('include_users')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Warning Notice -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>This will delete any existing cost sharing calculations for the selected period.</li>
                                        <li>Only approved expenses will be included in the calculation.</li>
                                        <li>Make sure all expenses for the period have been entered and approved before calculating.</li>
                                        <li>Users must have submitted meal preferences for accurate proportional calculations.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('cost-sharing.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Calculate Cost Sharing
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select_all');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    
    // Handle select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Update select all checkbox when individual checkboxes change
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(userCheckboxes).every(cb => cb.checked);
            const noneChecked = Array.from(userCheckboxes).every(cb => !cb.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = !allChecked && !noneChecked;
        });
    });
    
    // Set initial state
    const allChecked = Array.from(userCheckboxes).every(cb => cb.checked);
    selectAllCheckbox.checked = allChecked;
});
</script>
@endsection