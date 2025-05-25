<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\MealCategory;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meals = Meal::with(['mealCategory'])
            ->orderBy('meal_date', 'desc')
            ->orderBy('meal_time', 'asc')
            ->paginate(15);
        
        return view('meals.index', compact('meals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mealCategories = MealCategory::active()->get();
        return view('meals.create', compact('mealCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:breakfast,lunch,dinner',
            'meal_date' => 'required|date',
            'meal_time' => 'required|date_format:H:i',
            'meal_category_id' => 'required|exists:meal_categories,id',
            'description' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Meal::create($validated);

        return redirect()->route('meals.index')
            ->with('success', 'Meal created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Meal $meal)
    {
        $meal->load('category');
        return view('meals.show', compact('meal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meal $meal)
    {
        $categories = MealCategory::where('is_active', true)->get();
        return view('meals.edit', compact('meal', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meal $meal)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:breakfast,lunch,dinner,snack',
            'category_id' => 'required|exists:meal_categories,id',
            'estimated_cost' => 'required|numeric|min:0',
            'meal_date' => 'required|date',
            'meal_time' => 'required',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $meal->update([
            'name' => $request->name,
            'type' => $request->type,
            'category_id' => $request->category_id,
            'estimated_cost' => $request->estimated_cost,
            'meal_date' => $request->meal_date,
            'meal_time' => $request->meal_time,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('meals.index')
            ->with('success', 'Meal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        $meal->delete();

        return redirect()->route('meals.index')
            ->with('success', 'Meal deleted successfully.');
    }
}
