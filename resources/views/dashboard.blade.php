<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }} - {{ ucfirst($user->getRoleNames()->first()) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Welcome back, {{ $user->name }}!</h3>
                    <p class="text-gray-600">Today is {{ $today->format('l, F j, Y') }}</p>
                </div>
            </div>

            @if($user->hasRole('admin'))
                <!-- Admin Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">U</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $total_users }}</h4>
                                    <p class="text-sm text-gray-600">Total Users</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">A</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $active_users }}</h4>
                                    <p class="text-sm text-gray-600">Active Users</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">M</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $total_meals_today }}</h4>
                                    <p class="text-sm text-gray-600">Meals Today</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">$</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">${{ number_format($total_expenses_this_month, 2) }}</h4>
                                    <p class="text-sm text-gray-600">Monthly Expenses</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Bazaar Records</h3>
                            @if($recent_bazaar_records->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recent_bazaar_records as $record)
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-medium">{{ $record->item_name }}</p>
                                                <p class="text-sm text-gray-600">by {{ $record->user->name }}</p>
                                            </div>
                                            <span class="text-sm font-medium">${{ number_format($record->total_cost, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No recent bazaar records</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Expenses</h3>
                            @if($recent_expenses->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recent_expenses as $expense)
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-medium">{{ $expense->description }}</p>
                                                <p class="text-sm text-gray-600">{{ $expense->category }} - {{ $expense->status }}</p>
                                            </div>
                                            <span class="text-sm font-medium">${{ number_format($expense->amount, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No recent expenses</p>
                            @endif
                        </div>
                    </div>
                </div>

            @elseif($user->hasRole('manager'))
                <!-- Manager Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">M</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $total_meals_today }}</h4>
                                    <p class="text-sm text-gray-600">Meals Today</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">P</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $meal_preferences_today }}</h4>
                                    <p class="text-sm text-gray-600">Preferences Today</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">$</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">${{ number_format($total_expenses_this_month, 2) }}</h4>
                                    <p class="text-sm text-gray-600">Monthly Expenses</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bazaar Records -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Bazaar Records</h3>
                        @if($recent_bazaar_records->count() > 0)
                            <div class="space-y-3">
                                @foreach($recent_bazaar_records as $record)
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium">{{ $record->item_name }}</p>
                                            <p class="text-sm text-gray-600">{{ $record->purchase_date->format('M j, Y') }}</p>
                                        </div>
                                        <span class="text-sm font-medium">${{ number_format($record->total_cost, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No recent bazaar records</p>
                        @endif
                    </div>
                </div>

            @else
                <!-- User Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">P</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $my_preferences_today }}</h4>
                                    <p class="text-sm text-gray-600">My Preferences Today</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">$</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">${{ number_format($my_total_cost_this_month, 2) }}</h4>
                                    <p class="text-sm text-gray-600">My Cost This Month</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">B</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">${{ number_format($recent_bazaar_summary, 2) }}</h4>
                                    <p class="text-sm text-gray-600">Bazaar (Last 7 Days)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Meals -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Upcoming Meals Today</h3>
                        @if($upcoming_meals->count() > 0)
                            <div class="space-y-3">
                                @foreach($upcoming_meals as $meal)
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium">{{ $meal->meal_type }}</p>
                                            <p class="text-sm text-gray-600">{{ $meal->meal_time }}</p>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $meal->mealCategory->name ?? 'General' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No upcoming meals today</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-4">
                        @if($user->hasRole('admin'))
                            <a href="{{ route('users.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Manage Users
                            </a>
                            <a href="{{ route('expenses.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                View Expenses
                            </a>
                        @endif
                        
                        @if($user->hasRole('manager') || $user->hasRole('admin'))
                            <a href="{{ route('bazaar-records.index') }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Bazaar Records
                            </a>
                            <a href="{{ route('meals.index') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                Manage Meals
                            </a>
                        @endif
                        
                        <a href="{{ route('meal-preferences.index') }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            My Meal Preferences
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
