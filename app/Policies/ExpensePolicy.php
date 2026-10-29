<?php

namespace App\Policies;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
{
    

    public function create(User $user,Budget $Budget): Response
    {
        return $user->id === $Budget->user_id ? Response::allow() : Response::deny('No puedes agregar gastos a este presupuesto');
    }


    public function update(User $user, Expense $expense): Response
    {
        return $user->id === $expense->budget->user_id ? Response::allow() : Response::deny('No puedes actualizar gastos en este presupuesto');
    }

    public function delete(User $user, Expense $expense): Response
    {
        return $user->id === $expense->budget->user_id ? Response::allow() : Response::deny('No puedes eliminar gastos en este presupuesto');
    }

}
