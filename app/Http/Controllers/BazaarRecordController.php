<?php

namespace App\Http\Controllers;

use App\Models\BazaarRecord;
use Illuminate\Http\Request;

class BazaarRecordController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bazaarRecords = BazaarRecord::with('creator')
            ->latest('purchase_date')
            ->paginate(15);

        $totalCost = BazaarRecord::sum('total_cost');
        $monthlyTotal = BazaarRecord::whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('total_cost');

        return view('bazaar-records.index', compact('bazaarRecords', 'totalCost', 'monthlyTotal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bazaar-records.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'cost' => 'required|numeric|min:0.01',
            'purchase_date' => 'required|date',
            'vendor' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $quantity = $request->quantity;
        $totalCost = $request->cost;
        $unitPrice = $quantity > 0 ? $totalCost / $quantity : 0;

        BazaarRecord::create([
            'item_name' => $request->item_name,
            'quantity' => $quantity,
            'unit' => $request->unit,
            'unit_price' => $unitPrice,
            'total_cost' => $totalCost,
            'purchase_date' => $request->purchase_date,
            'vendor_name' => $request->vendor,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('bazaar-records.index')
            ->with('success', 'Bazaar record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BazaarRecord $bazaarRecord)
    {
        $bazaarRecord->load('creator');
        return view('bazaar-records.show', compact('bazaarRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BazaarRecord $bazaarRecord)
    {
        return view('bazaar-records.edit', compact('bazaarRecord'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BazaarRecord $bazaarRecord)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'cost' => 'required|numeric|min:0.01',
            'purchase_date' => 'required|date',
            'vendor' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $quantity = $request->quantity;
        $totalCost = $request->cost;
        $unitPrice = $quantity > 0 ? $totalCost / $quantity : 0;

        $bazaarRecord->update([
            'item_name' => $request->item_name,
            'quantity' => $quantity,
            'unit' => $request->unit,
            'unit_price' => $unitPrice,
            'total_cost' => $totalCost,
            'purchase_date' => $request->purchase_date,
            'vendor_name' => $request->vendor,
            'notes' => $request->notes,
        ]);

        return redirect()->route('bazaar-records.index')
            ->with('success', 'Bazaar record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BazaarRecord $bazaarRecord)
    {
        $bazaarRecord->delete();

        return redirect()->route('bazaar-records.index')
            ->with('success', 'Bazaar record deleted successfully.');
    }
}
