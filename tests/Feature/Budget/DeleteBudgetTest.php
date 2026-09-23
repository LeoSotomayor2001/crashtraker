<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;

uses(RefreshDatabase::class);

it('allows the owner to delete a budget', function () {
    /** @var User $user */
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create();
    $response = actingAs($user)->delete(route('budget.destroy', $budget));

    $response->assertRedirect(route('dashboard'));
    assertSoftDeleted('budgets', [
        'id' => $budget->id
    ]);
});

it('does not allow guests to delete budgets', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create();
    $response = delete(route('budget.destroy', $budget));
    $response->assertRedirect(route('login'));
    assertDatabaseHas('budgets', [
        'id' => $budget->id
    ]);
});

it('does not allow unverified users to delete budgets', function () {
    /** @var User $user */
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $budget = Budget::factory()->for($user)->create();
    $response = actingAs($user)->delete(route('budget.destroy', $budget));

    $response->assertRedirect(route('verification.notice'));
    assertDatabaseHas('budgets', [
        'id' => $budget->id
    ]);
});

it('does not allow other users to delete budgets', function () {

    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $otherUser = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $budget = Budget::factory()->for($owner)->create();

    $response = actingAs($otherUser)->delete(route('budget.destroy', $budget));
    $response->assertForbidden();
     assertDatabaseHas('budgets', [
        'id' => $budget->id
    ]);
});
