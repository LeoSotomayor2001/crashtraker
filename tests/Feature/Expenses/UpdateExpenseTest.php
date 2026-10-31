<?php

use App\Models\Budget;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

it('allows the expense owner to update an expense', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create([
        'name' => 'Supermercado',
        'amount' => 500,
        'category' => 'food',
    ]);

    $response = actingAs($user)->put(route('expenses.update', [$budget, $expense]), [
        'name' => 'Supermercado actualizado',
        'amount' => 700,
        'category' => 'food',
    ]);

    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHas('success', 'Gasto actualizado correctamente.');

    assertDatabaseHas('expenses', [
        'id' => $expense->id,
        'name' => 'Supermercado actualizado',
        'amount' => 700,
        'category' => 'food',
        'budget_id' => $budget->id,
    ]);
});

it('does not allow guests to update expenses', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create([
        'name' => 'Supermercado',
        'amount' => 500,
        'category' => 'food',
    ]);

    $response = put(route('expenses.update', [$budget, $expense]), [
        'name' => 'Supermercado actualizado',
        'amount' => 700,
        'category' => 'food',
    ]);

    $response->assertRedirect(route('login'));
    assertDatabaseHas('expenses', [
        'id' => $expense->id,
        'name' => 'Supermercado',
        'amount' => 500,
        'budget_id' => $budget->id,
    ]);
});

it('does not allow unverified users to update expenses', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create([
        'name' => 'Supermercado',
        'amount' => 500,
        'category' => 'food',
    ]);

    $response = actingAs($user)->put(route('expenses.update', [$budget, $expense]), [
        'name' => 'Supermercado actualizado',
        'amount' => 700,
        'category' => 'food',
    ]);

    $response->assertRedirect(route('verification.notice'));
    assertDatabaseHas('expenses', [
        'id' => $expense->id,
        'name' => 'Supermercado',
        'amount' => 500,
        'budget_id' => $budget->id,
    ]);
});

it('does not allow other users to update expenses they do not own', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $otherUser = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($owner)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create([
        'name' => 'Supermercado',
        'amount' => 500,
        'category' => 'food',
    ]);

    $response = actingAs($otherUser)->put(route('expenses.update', [$budget, $expense]), [
        'name' => 'Supermercado actualizado',
        'amount' => 700,
        'category' => 'food',
    ]);

    $response->assertForbidden();
    assertDatabaseHas('expenses', [
        'id' => $expense->id,
        'name' => 'Supermercado',
        'amount' => 500,
        'budget_id' => $budget->id,
    ]);
});

it('validates required fields when updating an expense in a general budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create([
        'name' => 'Supermercado',
        'amount' => 500,
        'category' => 'food',
    ]);

    $response = actingAs($user)
        ->from(route('budgets.show', $budget))
        ->put(route('expenses.update', [$budget, $expense]), [
            'name' => '',
            'amount' => '',
            'category' => '',
        ]);

    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHasErrors(['name', 'amount', 'category']);
    assertDatabaseHas('expenses', [
        'id' => $expense->id,
        'name' => 'Supermercado',
        'amount' => 500,
        'category' => 'food',
        'budget_id' => $budget->id,
    ]);
});
