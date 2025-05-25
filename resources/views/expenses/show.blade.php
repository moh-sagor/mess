@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Expense Details</h2>
                        <p class="text-gray-600">View expense information and approval status</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('expenses.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Expenses
                        </a>
                        @if($expense->status === 'pending' && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager')))
                            <a href="{{ route('expenses.edit', $expense) }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit Expense
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    @if($expense->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            Pending Approval
                        </span>
                    @elseif($expense->status === 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Approved
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Rejected
                        </span>
                    @endif
                </div>

                <!-- Expense Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Title</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $expense->title }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $expense->description ?: 'No description provided' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($expense->category) }}
                                    </span>
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Amount</label>
                                <p class="mt-1 text-lg font-semibold text-green-600">${{ number_format($expense->amount, 2) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Expense Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $expense->expense_date->format('F j, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                        
                        <div class="space-y-4">
                            @if($expense->bazaarRecord)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Related Bazaar Record</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        <a href="{{ route('bazaar-records.show', $expense->bazaarRecord) }}" 
                                           class="text-blue-600 hover:text-blue-800">
                                            {{ $expense->bazaarRecord->item_name }} - {{ $expense->bazaarRecord->purchase_date->format('M j, Y') }}
                                        </a>
                                    </p>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Created By</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $expense->creator->name }}</p>
                                <p class="text-xs text-gray-500">{{ $expense->created_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>

                            @if($expense->status !== 'pending')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ $expense->status === 'approved' ? 'Approved By' : 'Rejected By' }}
                                    </label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $expense->approver->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ $expense->approved_at?->format('F j, Y \a\t g:i A') }}</p>
                                </div>
                            @endif

                            @if($expense->status === 'rejected' && $expense->rejection_reason)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                                    <p class="mt-1 text-sm text-red-600 bg-red-50 p-3 rounded">{{ $expense->rejection_reason }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Admin Actions -->
                @role('admin')
                    @if($expense->status === 'pending')
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Actions</h3>
                            <div class="flex space-x-4">
                                <!-- Approve Button -->
                                <form action="{{ route('expenses.approve', $expense) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Are you sure you want to approve this expense?')">
                                        Approve Expense
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <button type="button" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="document.getElementById('rejectModal').classList.remove('hidden')">
                                    Reject Expense
                                </button>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                <div class="mt-3">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Expense</h3>
                                    <form action="{{ route('expenses.reject', $expense) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="mb-4">
                                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                                Reason for Rejection *
                                            </label>
                                            <textarea name="rejection_reason" id="rejection_reason" rows="4" 
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                      placeholder="Please provide a reason for rejecting this expense..."
                                                      required></textarea>
                                        </div>
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" 
                                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                                                    onclick="document.getElementById('rejectModal').classList.add('hidden')">
                                                Cancel
                                            </button>
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                Reject Expense
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endrole
            </div>
        </div>
    </div>
</div>
@endsection