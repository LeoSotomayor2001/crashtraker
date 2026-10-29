<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Gate;

class ExpenseController extends Controller
{
   
    public function store(ExpenseRequest $request, Budget $budget)
    {   
        Gate::authorize('create', [Expense::class,$budget]);
        $data= $request->validated();

        // Expense::create([
        //     'name' => $data['name'],
        //     'amount' => $data['amount'],
        //     'category' => $data['category'],
        //     'budget_id' => $budget->id 
        // ]);

        $budget->expenses()->create($data);

        return redirect()->route('budgets.show',$budget)->with('success','Gasto creado correctamente.');
    }

   
    #[Authorize('update','expense')]
    public function update(ExpenseRequest $request,Budget $budget, Expense $expense)
    {
        $expense->update($request->validated());
        return redirect()->route('budgets.show',$budget)->with('success','Gasto actualizado correctamente.');
    }

    #[Authorize('delete','expense')]
    public function destroy(Budget $budget, Expense $expense)
    {
        $expense->delete();

        return redirect()->route('budgets.show',$budget)->with('success','Gasto Eliminado correctamente.');
    }
}
