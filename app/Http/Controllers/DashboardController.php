<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Meal;
use App\Models\MealPreference;
use App\Models\BazaarRecord;
use App\Models\Expense;
use App\Models\CostSharing;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        
        // Common data for all roles
        $data = [
            'user' => $user,
            'today' => Carbon::today(),
        ];

        // Role-specific data
        if ($user->hasRole('admin')) {
            $data = array_merge($data, $this->getAdminData());
        } elseif ($user->hasRole('manager')) {
            $data = array_merge($data, $this->getManagerData());
        } else {
            $data = array_merge($data, $this->getUserData());
        }

        return view('dashboard', $data);
    }

    private function getAdminData()
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_meals_today' => Meal::whereDate('meal_date', Carbon::today())->count(),
            'total_expenses_this_month' => Expense::whereMonth('expense_date', Carbon::now()->month)
                ->whereYear('expense_date', Carbon::now()->year)
                ->sum('amount'),
            'pending_expenses' => Expense::where('status', 'pending')->count(),
            'recent_bazaar_records' => BazaarRecord::with('user')
                ->latest()
                ->take(5)
                ->get(),
            'recent_expenses' => Expense::with('user')
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    private function getManagerData()
    {
        return [
            'total_meals_today' => Meal::whereDate('meal_date', Carbon::today())->count(),
            'meal_preferences_today' => MealPreference::whereHas('meal', function($query) {
                $query->whereDate('meal_date', Carbon::today());
            })->count(),
            'total_expenses_this_month' => Expense::whereMonth('expense_date', Carbon::now()->month)
                ->whereYear('expense_date', Carbon::now()->year)
                ->sum('amount'),
            'recent_bazaar_records' => BazaarRecord::with('user')
                ->latest()
                ->take(5)
                ->get(),
            'pending_meal_preferences' => MealPreference::whereHas('meal', function($query) {
                $query->whereDate('meal_date', Carbon::today());
            })->where('status', 'pending')->count(),
        ];
    }

    private function getUserData()
    {
        $user = auth()->user();
        
        return [
            'my_preferences_today' => MealPreference::where('user_id', $user->id)
                ->whereHas('meal', function($query) {
                    $query->whereDate('meal_date', Carbon::today());
                })
                ->count(),
            'my_total_cost_this_month' => CostSharing::where('user_id', $user->id)
                ->whereMonth('period_start', Carbon::now()->month)
                ->whereYear('period_start', Carbon::now()->year)
                ->sum('amount'),
            'upcoming_meals' => Meal::whereDate('meal_date', Carbon::today())
                ->where('meal_time', '>', Carbon::now()->format('H:i:s'))
                ->orderBy('meal_time')
                ->take(3)
                ->get(),
            'recent_bazaar_summary' => BazaarRecord::whereDate('purchase_date', '>=', Carbon::now()->subDays(7))
                ->sum('total_cost'),
        ];
    }
}
