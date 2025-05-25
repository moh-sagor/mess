@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Cost Sharing Summary</h2>
                        <p class="text-gray-600">{{ \Carbon\Carbon::parse($periodStart)->format('M d') }} - {{ \Carbon\Carbon::parse($periodEnd)->format('M d, Y') }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('cost-sharing.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List
                        </a>
                        @if(auth()->user()->hasRole(['admin', 'manager']))
                        <a href="{{ route('cost-sharing.create') }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            New Calculation
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Period Filter -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <form method="GET" action="{{ route('cost-sharing.summary') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="period_start" class="block text-sm font-medium text-gray-700">Period Start</label>
                            <input type="date" name="period_start" id="period_start" 
                                   value="{{ $periodStart }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="period_end" class="block text-sm font-medium text-gray-700">Period End</label>
                            <input type="date" name="period_end" id="period_end" 
                                   value="{{ $periodEnd }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                                Update Summary
                            </button>
                        </div>
                    </form>
                </div>

                @if($costSharings->count() > 0)
                    <!-- Summary Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total Meals Cost</p>
                                    <p class="text-2xl font-bold text-gray-900">${{ number_format($summary['total_meals_cost'], 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Other Expenses</p>
                                    <p class="text-2xl font-bold text-gray-900">${{ number_format($summary['total_other_expenses'], 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total Paid</p>
                                    <p class="text-2xl font-bold text-gray-900">${{ number_format($summary['total_paid'], 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Outstanding</p>
                                    <p class="text-2xl font-bold text-gray-900">${{ number_format($summary['total_pending'] + $summary['total_calculated'], 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Users Included</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $summary['users_count'] }}</p>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Average Share</h3>
                            <p class="text-3xl font-bold text-green-600">${{ number_format($summary['average_share'], 2) }}</p>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Total Amount</h3>
                            <p class="text-3xl font-bold text-purple-600">${{ number_format($summary['total_amount'], 2) }}</p>
                        </div>
                    </div>

                    <!-- Payment Status Chart -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Status Overview</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="relative pt-1">
                                    <div class="flex mb-2 items-center justify-between">
                                        <div>
                                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200">
                                                Paid
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-semibold inline-block text-green-600">
                                                {{ $summary['total_amount'] > 0 ? number_format(($summary['total_paid'] / $summary['total_amount']) * 100, 1) : 0 }}%
                                            </span>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-green-200">
                                        <div style="width:{{ $summary['total_amount'] > 0 ? ($summary['total_paid'] / $summary['total_amount']) * 100 : 0 }}%" 
                                             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"></div>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600">${{ number_format($summary['total_paid'], 2) }}</p>
                            </div>

                            <div class="text-center">
                                <div class="relative pt-1">
                                    <div class="flex mb-2 items-center justify-between">
                                        <div>
                                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-yellow-600 bg-yellow-200">
                                                Pending
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-semibold inline-block text-yellow-600">
                                                {{ $summary['total_amount'] > 0 ? number_format(($summary['total_pending'] / $summary['total_amount']) * 100, 1) : 0 }}%
                                            </span>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-yellow-200">
                                        <div style="width:{{ $summary['total_amount'] > 0 ? ($summary['total_pending'] / $summary['total_amount']) * 100 : 0 }}%" 
                                             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-yellow-500"></div>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600">${{ number_format($summary['total_pending'], 2) }}</p>
                            </div>

                            <div class="text-center">
                                <div class="relative pt-1">
                                    <div class="flex mb-2 items-center justify-between">
                                        <div>
                                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-gray-600 bg-gray-200">
                                                Calculated
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-semibold inline-block text-gray-600">
                                                {{ $summary['total_amount'] > 0 ? number_format(($summary['total_calculated'] / $summary['total_amount']) * 100, 1) : 0 }}%
                                            </span>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                                        <div style="width:{{ $summary['total_amount'] > 0 ? ($summary['total_calculated'] / $summary['total_amount']) * 100 : 0 }}%" 
                                             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gray-500"></div>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600">${{ number_format($summary['total_calculated'], 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- User Breakdown Table -->
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">User Breakdown</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Share Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participation</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($costSharings as $costSharing)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">
                                                                {{ substr($costSharing->user->name, 0, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $costSharing->user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $costSharing->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">${{ number_format($costSharing->user_share_amount, 2) }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ number_format(($costSharing->user_share_amount / $summary['total_amount']) * 100, 1) }}% of total
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $costSharing->meals_participated }}/{{ $costSharing->total_meals_available }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ number_format($costSharing->participation_percentage, 1) }}%
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $costSharing->sharing_method === 'equal' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ ucfirst($costSharing->sharing_method) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($costSharing->status === 'paid') bg-green-100 text-green-800
                                                    @elseif($costSharing->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($costSharing->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('cost-sharing.show', $costSharing) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No cost sharing data</h3>
                        <p class="mt-1 text-sm text-gray-500">No cost sharing calculations found for the selected period.</p>
                        @if(auth()->user()->hasRole(['admin', 'manager']))
                            <div class="mt-6">
                                <a href="{{ route('cost-sharing.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Calculate Cost Sharing
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection