<?php

namespace App\Http\Controllers;

use App\Models\CostSharing;
use App\Models\User;
use App\Models\Expense;
use App\Models\MealPreference;
use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CostSharingController extends Controller
{
    /**
     * Display a listing of cost sharing records.
     */
    public function index(Request $request)
    {
        $query = CostSharing::with('user');
        
        // Filter by period if provided
        if ($request->filled('period_start') && $request->filled('period_end')) {
            $query->forPeriod($request->period_start, $request->period_end);
        }
        
        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        $costSharings = $query->orderBy('period_start', 'desc')
                            ->orderBy('user_id')
                            ->paginate(20);
        
        // Get summary statistics
        $totalAmount = CostSharing::sum('user_share_amount');
        $totalPaid = CostSharing::paid()->sum('user_share_amount');
        $totalPending = CostSharing::pending()->sum('user_share_amount');
        $totalCalculated = CostSharing::calculated()->sum('user_share_amount');
        
        $users = User::orderBy('name')->get();
        
        return view('cost-sharing.index', compact(
            'costSharings', 
            'totalAmount', 
            'totalPaid', 
            'totalPending', 
            'totalCalculated',
            'users'
        ));
    }

    /**
     * Show the form for creating a new cost sharing calculation.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        
        // Get suggested period (current month)
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        
        return view('cost-sharing.create', compact('users', 'startDate', 'endDate'));
    }

    /**
     * Calculate cost sharing for a specific period.
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'sharing_method' => 'required|in:equal,proportional',
            'include_users' => 'array',
            'include_users.*' => 'exists:users,id'
        ]);

        $periodStart = Carbon::parse($request->period_start);
        $periodEnd = Carbon::parse($request->period_end);
        $sharingMethod = $request->sharing_method;
        $includeUsers = $request->include_users ?? User::pluck('id')->toArray();

        try {
            DB::beginTransaction();

            // Delete existing calculations for this period
            CostSharing::whereBetween('period_start', [$periodStart, $periodEnd])
                      ->orWhereBetween('period_end', [$periodStart, $periodEnd])
                      ->delete();

            // Get all approved expenses for the period
            $expenses = Expense::where('status', 'approved')
                             ->whereBetween('expense_date', [$periodStart, $periodEnd])
                             ->get();

            $totalMealsCost = $expenses->where('category', 'food')->sum('amount');
            $totalOtherExpenses = $expenses->whereNotIn('category', ['food'])->sum('amount');

            // Get all meals in the period
            $meals = Meal::whereBetween('meal_date', [$periodStart, $periodEnd])->get();
            $totalMealsAvailable = $meals->count();

            // Get users to include in calculation
            $users = User::whereIn('id', $includeUsers)->get();

            foreach ($users as $user) {
                // Count meals participated by user
                $mealsParticipated = MealPreference::where('user_id', $user->id)
                    ->whereIn('meal_id', $meals->pluck('id'))
                    ->where('is_participating', true)
                    ->count();

                // Calculate user share based on method
                if ($sharingMethod === 'equal') {
                    $userShareAmount = ($totalMealsCost + $totalOtherExpenses) / $users->count();
                } else { // proportional
                    if ($totalMealsAvailable > 0) {
                        $participationRatio = $mealsParticipated / $totalMealsAvailable;
                        $userMealShare = $totalMealsCost * $participationRatio;
                        $userOtherShare = $totalOtherExpenses / $users->count(); // Other expenses split equally
                        $userShareAmount = $userMealShare + $userOtherShare;
                    } else {
                        $userShareAmount = $totalOtherExpenses / $users->count();
                    }
                }

                // Create cost sharing record
                CostSharing::create([
                    'user_id' => $user->id,
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd,
                    'total_meals_cost' => $totalMealsCost,
                    'total_other_expenses' => $totalOtherExpenses,
                    'user_share_amount' => round($userShareAmount, 2),
                    'meals_participated' => $mealsParticipated,
                    'total_meals_available' => $totalMealsAvailable,
                    'sharing_method' => $sharingMethod,
                    'status' => 'calculated'
                ]);
            }

            DB::commit();

            return redirect()->route('cost-sharing.index')
                           ->with('success', 'Cost sharing calculated successfully for ' . $users->count() . ' users.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Error calculating cost sharing: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified cost sharing record.
     */
    public function show(CostSharing $costSharing)
    {
        $costSharing->load('user');
        
        // Get related expenses for the period
        $expenses = Expense::where('status', 'approved')
                          ->whereBetween('expense_date', [$costSharing->period_start, $costSharing->period_end])
                          ->orderBy('expense_date', 'desc')
                          ->get();
        
        // Get user's meal preferences for the period
        $meals = Meal::whereBetween('meal_date', [$costSharing->period_start, $costSharing->period_end])
                    ->with(['preferences' => function($query) use ($costSharing) {
                        $query->where('user_id', $costSharing->user_id);
                    }])
                    ->orderBy('meal_date', 'desc')
                    ->get();
        
        return view('cost-sharing.show', compact('costSharing', 'expenses', 'meals'));
    }

    /**
     * Update the payment status of cost sharing record.
     */
    public function updateStatus(Request $request, CostSharing $costSharing)
    {
        $request->validate([
            'status' => 'required|in:calculated,paid,pending'
        ]);

        $costSharing->update([
            'status' => $request->status
        ]);

        return redirect()->back()
                       ->with('success', 'Cost sharing status updated successfully.');
    }

    /**
     * Remove the specified cost sharing record.
     */
    public function destroy(CostSharing $costSharing)
    {
        $costSharing->delete();

        return redirect()->route('cost-sharing.index')
                       ->with('success', 'Cost sharing record deleted successfully.');
    }

    /**
     * Show cost sharing summary for a specific period.
     */
    public function summary(Request $request)
    {
        $periodStart = $request->get('period_start', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $periodEnd = $request->get('period_end', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $costSharings = CostSharing::with('user')
                                  ->forPeriod($periodStart, $periodEnd)
                                  ->orderBy('user_share_amount', 'desc')
                                  ->get();

        $summary = [
            'total_meals_cost' => $costSharings->first()->total_meals_cost ?? 0,
            'total_other_expenses' => $costSharings->first()->total_other_expenses ?? 0,
            'total_amount' => $costSharings->sum('user_share_amount'),
            'total_paid' => $costSharings->where('status', 'paid')->sum('user_share_amount'),
            'total_pending' => $costSharings->where('status', 'pending')->sum('user_share_amount'),
            'total_calculated' => $costSharings->where('status', 'calculated')->sum('user_share_amount'),
            'users_count' => $costSharings->count(),
            'average_share' => $costSharings->count() > 0 ? $costSharings->avg('user_share_amount') : 0,
        ];

        return view('cost-sharing.summary', compact('costSharings', 'summary', 'periodStart', 'periodEnd'));
    }

    /**
     * Bulk update status for multiple cost sharing records.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'cost_sharing_ids' => 'required|array',
            'cost_sharing_ids.*' => 'exists:cost_sharing,id',
            'status' => 'required|in:calculated,paid,pending'
        ]);

        CostSharing::whereIn('id', $request->cost_sharing_ids)
                   ->update(['status' => $request->status]);

        return redirect()->back()
                       ->with('success', 'Status updated for ' . count($request->cost_sharing_ids) . ' records.');
    }
}
