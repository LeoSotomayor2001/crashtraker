<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

it('allows the owner to update a budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'name' => 'sass',
        'amount' => 100,
        'type' => 'general'
    ]);

    $response = actingAs($user)->put(route('budget.update', $budget), [
        'name' => 'sass Actu',
        'amount' => 1010,
        'type' => 'goal'
    ]);

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('success', 'Presupuesto actualizado correctamente.');

    assertDatabaseHas('budgets', [
        'id' => $budget->id,
        'name' => 'sass Actu',
        'amount' => 1010,
        'type' => 'goal',
        'user_id' => $user->id
    ]);
});

it('validates required fields when updating a budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create();

    $response = actingAs($user)->from(route('budget.edit', $budget))->put(route('budget.update', $budget), [
        'name' => '',
        'amount' => '',
        'type' => ''
    ]);

    $response->assertRedirect(route('budget.edit', $budget));
    $response->assertSessionHasErrors([
        'name',
        'amount',
        'type'
    ]);
});

it('does not allow guests to update budgets', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create();
    $response = put(route('budget.update', $budget), [
        'name' => 'sass Actu',
        'amount' => 1010,
        'type' => 'goal'
    ]);

    $response->assertRedirect(route('login'));
});

it('does not allow other users to update budgets', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $otherUser = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($owner)->create(['name' => 'nombre origunal']);

    $response= actingAs($otherUser)->put(route('budget.update',$budget),[
        'name' => 'actualizado',
        'amount' => 2222
    ]);

    $response->assertForbidden();

    assertDatabaseHas('budgets',[
        'id' => $budget->id,
        'name' => 'nombre origunal'
    ]);
});
