@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Cost Sharing Details</h2>
                        <p class="text-gray-600">{{ $costSharing->user->name }} - {{ $costSharing->period_start->format('M d') }} to {{ $costSharing->period_end->format('M d, Y') }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('cost-sharing.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List
                        </a>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                        @if($costSharing->status === 'paid') bg-green-100 text-green-800
                        @elseif($costSharing->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800 @endif">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            @if($costSharing->status === 'paid')
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            @elseif($costSharing->status === 'pending')
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            @else
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            @endif
                        </svg>
                        {{ ucfirst($costSharing->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Cost Breakdown -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Summary Card -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-lg border border-blue-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Cost Summary</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Total Share Amount</p>
                                    <p class="text-2xl font-bold text-blue-600">${{ number_format($costSharing->user_share_amount, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Sharing Method</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($costSharing->sharing_method) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Breakdown -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Detailed Breakdown</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Total Meals Cost (Period)</span>
                                    <span class="font-medium">${{ number_format($costSharing->total_meals_cost, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Total Other Expenses (Period)</span>
                                    <span class="font-medium">${{ number_format($costSharing->total_other_expenses, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Total Period Cost</span>
                                    <span class="font-medium">${{ number_format($costSharing->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 font-semibold text-lg">
                                    <span class="text-gray-900">Your Share</span>
                                    <span class="text-blue-600">${{ number_format($costSharing->user_share_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Meal Participation -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Meal Participation</h3>
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-green-600">{{ $costSharing->meals_participated }}</p>
                                    <p class="text-sm text-gray-600">Meals Participated</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-600">{{ $costSharing->total_meals_available }}</p>
                                    <p class="text-sm text-gray-600">Total Meals Available</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-blue-600">{{ number_format($costSharing->participation_percentage, 1) }}%</p>
                                    <p class="text-sm text-gray-600">Participation Rate</p>
                                </div>
                            </div>
                            
                            <!-- Participation Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $costSharing->participation_percentage }}%"></div>
                            </div>
                        </div>

                        @if(auth()->user()->hasRole('admin'))
                        <!-- Admin Actions -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Actions</h3>
                            <div class="flex space-x-3">
                                @if($costSharing->status !== 'paid')
                                    <form method="POST" action="{{ route('cost-sharing.update-status', $costSharing) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="paid">
                                        <button type="submit" 
                                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            Mark as Paid
                                        </button>
                                    </form>
                                @endif
                                
                                @if($costSharing->status !== 'pending')
                                    <form method="POST" action="{{ route('cost-sharing.update-status', $costSharing) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="pending">
                                        <button type="submit" 
                                                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                            Mark as Pending
                                        </button>
                                    </form>
                                @endif
                                
                                @if($costSharing->status !== 'calculated')
                                    <form method="POST" action="{{ route('cost-sharing.update-status', $costSharing) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="calculated">
                                        <button type="submit" 
                                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                            Mark as Calculated
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- User Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Name</p>
                                    <p class="font-medium">{{ $costSharing->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Email</p>
                                    <p class="font-medium">{{ $costSharing->user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Role</p>
                                    <p class="font-medium">{{ ucfirst($costSharing->user->getRoleNames()->first()) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Period Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Period Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Start Date</p>
                                    <p class="font-medium">{{ $costSharing->period_start->format('F d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">End Date</p>
                                    <p class="font-medium">{{ $costSharing->period_end->format('F d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Duration</p>
                                    <p class="font-medium">{{ $costSharing->period_start->diffInDays($costSharing->period_end) + 1 }} days</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Stats</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Expenses Count</span>
                                    <span class="font-medium">{{ $expenses->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Meals Count</span>
                                    <span class="font-medium">{{ $meals->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Avg. Daily Cost</span>
                                    <span class="font-medium">
                                        ${{ number_format($costSharing->user_share_amount / ($costSharing->period_start->diffInDays($costSharing->period_end) + 1), 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Expenses -->
                @if($expenses->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Related Expenses ({{ $expenses->count() }})</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($expenses as $expense)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $expense->expense_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('expenses.show', $expense) }}" 
                                               class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                                {{ $expense->title }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $expense->category === 'food' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($expense->category) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ${{ number_format($expense->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- User's Meal Preferences -->
                @if($meals->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Meal Participation ({{ $meals->count() }} meals)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($meals as $meal)
                            @php
                                $preference = $meal->preferences->first();
                                $isParticipating = $preference && $preference->is_participating;
                            @endphp
                            <div class="border border-gray-200 rounded-lg p-4 {{ $isParticipating ? 'bg-green-50 border-green-200' : 'bg-gray-50' }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $meal->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $meal->meal_date->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $meal->meal_time }}</p>
                                    </div>
                                    <div>
                                        @if($isParticipating)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Participated
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Not Participated
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection