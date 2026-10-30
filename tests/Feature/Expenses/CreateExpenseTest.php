<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('allows the budget owner to create an expense in a general budget', function () {
    /** @var User $user */
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general'
    ]);

    $response = actingAs($user)->post(route('expenses.store', $budget), [
        'name' => 'Dentista',
        'amount' => 300.55,
        'category' => 'health'
    ]);

    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHas('success', 'Gasto creado correctamente.');

    assertDatabaseHas('expenses', [
        'name' => 'Dentista',
        'amount' => 300.55,
        'category' => 'health',
        'budget_id' => $budget->id
    ]);
});

it('allows the budget owner to create an expense in a goal budget without category', function () {
    /** @var User $user */
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'goal'
    ]);

    $response = actingAs($user)->post(route('expenses.store', $budget), [
        'name' => 'Dentista',
        'amount' => 300.55
    ]);

    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHas('success', 'Gasto creado correctamente.');

    assertDatabaseHas('expenses', [
        'name' => 'Dentista',
        'amount' => 300.55,
        'budget_id' => $budget->id
    ]);
});

it('does not allow guests to create expenses', function () {
      /** @var User $user */
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'goal'
    ]);

    $response=post(route('expenses.store', $budget), [
        'name' => 'Dentista',
        'amount' => 300.55
    ]);

    $response->assertRedirect(route('login'));
});

it('does not allow unverified users to create expenses', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general'
    ]);

    $response = actingAs($user)->post(route('expenses.store', $budget), [
        'name' => 'Dentista',
        'amount' => 300.55,
        'category' => 'health'
    ]);

    $response->assertRedirect(route('verification.notice'));
    assertDatabaseMissing('expenses', [
        'name' => 'Dentista',
        'budget_id' => $budget->id,
    ]);
});

it('does not allow other users to create expenses in someone else budget', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $otherUser = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($owner)->create([
        'type' => 'general'
    ]);

    $response = actingAs($otherUser)->post(route('expenses.store', $budget), [
        'name' => 'Dentista',
        'amount' => 300.55,
        'category' => 'health'
    ]);

    $response->assertForbidden();
    assertDatabaseMissing('expenses', [
        'name' => 'Dentista',
        'budget_id' => $budget->id,
    ]);
});

it('validates required fields when creating an expense in a general budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general'
    ]);

    $response = actingAs($user)->from(route('budgets.show', $budget))->post(route('expenses.store', $budget), [
        'name' => '',
        'amount' => '',
        'category' => ''
    ]);

    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHasErrors(['name', 'amount', 'category']);
    assertDatabaseMissing('expenses', [
        'budget_id' => $budget->id,
    ]);
});

it('validates category must be valid for a general budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general'
    ]);

    $response = actingAs($user)->from(route('budgets.show', $budget))->post(route('expenses.store', $budget), [
        'name' => 'Dentista',
        'amount' => 300.55,
        'category' => 'invalid-category'
    ]);

    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHasErrors(['category']);
    assertDatabaseMissing('expenses', [
        'name' => 'Dentista',
        'budget_id' => $budget->id,
    ]);
});

it('does not require category for a goal budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'goal'
    ]);

    $response = actingAs($user)->post(route('expenses.store', $budget), [
        'name' => 'Dentista',
        'amount' => 300.55,
    ]);

    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHas('success', 'Gasto creado correctamente.');

    assertDatabaseHas('expenses', [
        'name' => 'Dentista',
        'amount' => 300.55,
        'budget_id' => $budget->id
    ]);
});
