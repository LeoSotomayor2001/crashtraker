<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetRequest;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{

    public function index()
    {
        $budgets = Auth::user()->budgets()->get();

        return view('dashboard', ['budgets' => $budgets]);
    }

    public function create()
    {
        return view('budgets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BudgetRequest $request)
    {
        $data = $request->validated();

        // $budget= Budget::create([
        //     'name' => $data['name'],
        //     'amount' => $data['amount'],
        //     'type' => $data['type'],
        //     'user_id' => Auth::id()
        // ]);

        $budget = Auth::user()->budgets()->create($data);

        return redirect()->route('dashboard')->with('success', 'Presupuesto creado correctamente.');
    }


    public function show(string $id)
    {
        //
    }

    #[Authorize('update', 'budget')]
    public function edit(Budget $budget)
    {

        return view('budgets.edit', [
            'budget' => $budget
        ]);
    }

    #[Authorize('update', 'budget')]
    public function update(BudgetRequest $request, Budget $budget)
    {
        $budget->update($request->validated());
        return redirect()->route('dashboard')->with('success', 'Presupuesto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
