<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('show empty state when the user has no budgets', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertOk();
});

it('show not empty state when the user has budgets for authenticated user only', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);
    $user2 = User::factory()->create([
        'email_verified_at' => now()
    ]);

    Budget::factory()->for($user)->create([
        'name' => 'Mi presu'
    ]);

     Budget::factory()->for($user2)->create([
        'name' => 'Mi presu2'
    ]);

    $response= $this->actingAs($user)->get(route('dashboard'));
    $response->assertOk();
    $response->assertSee('Mi presu');
    $response->assertDontSee('Mi presu2');

});
