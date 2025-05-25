<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\BazaarRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::with(['creator', 'approver', 'bazaarRecord'])
            ->orderBy('expense_date', 'desc')
            ->paginate(15);

        $stats = [
            'total_expenses' => Expense::sum('amount'),
            'pending_approval' => Expense::where('status', 'pending')->count(),
            'approved_this_month' => Expense::where('status', 'approved')
                ->whereMonth('expense_date', now()->month)
                ->whereYear('expense_date', now()->year)
                ->sum('amount'),
            'monthly_expenses' => Expense::whereMonth('expense_date', now()->month)
                ->whereYear('expense_date', now()->year)
                ->sum('amount'),
        ];

        return view('expenses.index', compact('expenses', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bazaarRecords = BazaarRecord::orderBy('purchase_date', 'desc')->get();
        $categories = [
            'food' => 'Food & Groceries',
            'utilities' => 'Utilities',
            'maintenance' => 'Maintenance',
            'supplies' => 'Supplies',
            'transportation' => 'Transportation',
            'miscellaneous' => 'Miscellaneous'
        ];

        return view('expenses.create', compact('bazaarRecords', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|in:food,utilities,maintenance,supplies,transportation,miscellaneous',
            'expense_date' => 'required|date',
            'bazaar_record_id' => 'nullable|exists:bazaar_records,id',
            'receipt_number' => 'nullable|string|max:100',
        ]);

        Expense::create([
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'category' => $request->category,
            'expense_date' => $request->expense_date,
            'bazaar_record_id' => $request->bazaar_record_id,
            'receipt_number' => $request->receipt_number,
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully and is pending approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $expense->load(['creator', 'approver', 'bazaarRecord']);
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);
        
        $bazaarRecords = BazaarRecord::orderBy('purchase_date', 'desc')->get();
        $categories = [
            'food' => 'Food & Groceries',
            'utilities' => 'Utilities',
            'maintenance' => 'Maintenance',
            'supplies' => 'Supplies',
            'transportation' => 'Transportation',
            'miscellaneous' => 'Miscellaneous'
        ];

        return view('expenses.edit', compact('expense', 'bazaarRecords', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|in:food,utilities,maintenance,supplies,transportation,miscellaneous',
            'expense_date' => 'required|date',
            'bazaar_record_id' => 'nullable|exists:bazaar_records,id',
            'receipt_number' => 'nullable|string|max:100',
        ]);

        $expense->update([
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'category' => $request->category,
            'expense_date' => $request->expense_date,
            'bazaar_record_id' => $request->bazaar_record_id,
            'receipt_number' => $request->receipt_number,
        ]);

        return redirect()->route('expenses.show', $expense)
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    /**
     * Approve an expense
     */
    public function approve(Expense $expense)
    {
        $this->authorize('approve', $expense);

        $expense->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Expense approved successfully.');
    }

    /**
     * Reject an expense
     */
    public function reject(Request $request, Expense $expense)
    {
        $this->authorize('approve', $expense);

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $expense->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->back()
            ->with('success', 'Expense rejected.');
    }
}
