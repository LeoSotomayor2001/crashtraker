<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);
it('allows the owner to view the edit budget form', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget= Budget::factory()->for($user)->create([
        'name' => 'sa',
        'amount' => 100,
        'type' => 'general'
    ]);

    $response = $this->actingAs($user)->get(route('budget.edit',$budget));
    $response->assertOk();

});

it('does not allow guests to view the edit budget form', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

     $budget= Budget::factory()->for($user)->create([
        'name' => 'sass',
        'amount' => 100,
        'type' => 'general'
    ]);

    $response=$this->get(route('budget.edit',$budget));

    $response->assertRedirect(route('login'));
});

it('does not allow other users to view the edit budget form', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    
    $otherUser = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget=Budget::factory()->for($owner)->create();
    $response = actingAs($otherUser)->get(route('budget.edit', $budget));
    $response->assertSee('403');
    $response->assertForbidden();
    $response->assertStatus(403);

});
