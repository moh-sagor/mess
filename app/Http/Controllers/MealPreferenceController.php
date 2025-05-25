<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\MealPreference;
use Illuminate\Http\Request;

class MealPreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            // Admin/Manager can see all preferences
            $preferences = MealPreference::with(['meal.mealCategory', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Users can only see their own preferences
            $preferences = MealPreference::with(['meal.mealCategory'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }
        
        return view('meal-preferences.index', compact('preferences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get available meals for today and upcoming days
        $meals = Meal::active()
            ->where('meal_date', '>=', now()->toDateString())
            ->with('mealCategory')
            ->orderBy('meal_date')
            ->orderBy('meal_time')
            ->get();
        
        return view('meal-preferences.create', compact('meals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'meal_id' => 'required|exists:meals,id',
            'opted_in' => 'boolean',
            'special_requirements' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['opted_in'] = $request->has('opted_in');

        // Check if preference already exists
        $existingPreference = MealPreference::where('user_id', auth()->id())
            ->where('meal_id', $validated['meal_id'])
            ->first();

        if ($existingPreference) {
            $existingPreference->update($validated);
            $message = 'Meal preference updated successfully.';
        } else {
            MealPreference::create($validated);
            $message = 'Meal preference saved successfully.';
        }

        return redirect()->route('meal-preferences.index')
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(MealPreference $mealPreference)
    {
        // Check if user can view this preference
        if (!auth()->user()->hasRole(['admin', 'manager']) && auth()->id() !== $mealPreference->user_id) {
            abort(403, 'Unauthorized to view this preference.');
        }

        $mealPreference->load(['user', 'meal.mealCategory']);
        
        return view('meal-preferences.show', compact('mealPreference'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MealPreference $mealPreference)
    {
        // Check if user can edit this preference
        if (!auth()->user()->hasRole(['admin', 'manager']) && auth()->id() !== $mealPreference->user_id) {
            abort(403, 'Unauthorized to edit this preference.');
        }

        $mealPreference->load(['user', 'meal.mealCategory']);
        
        return view('meal-preferences.edit', compact('mealPreference'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MealPreference $mealPreference)
    {
        // Check if user can update this preference
        if (!auth()->user()->hasRole(['admin', 'manager']) && auth()->id() !== $mealPreference->user_id) {
            abort(403, 'Unauthorized to update this preference.');
        }

        $request->validate([
            'opted_in' => 'boolean',
            'special_requirements' => 'nullable|string|max:500',
        ]);

        $mealPreference->update([
            'opted_in' => $request->boolean('opted_in'),
            'special_requirements' => $request->special_requirements,
        ]);

        return redirect()->route('meal-preferences.index')
            ->with('success', 'Meal preference updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MealPreference $mealPreference)
    {
        // Check if user can delete this preference
        if (!auth()->user()->hasRole(['admin', 'manager']) && auth()->id() !== $mealPreference->user_id) {
            abort(403, 'Unauthorized to delete this preference.');
        }

        $mealPreference->delete();

        return redirect()->route('meal-preferences.index')
            ->with('success', 'Meal preference deleted successfully.');
    }
}
