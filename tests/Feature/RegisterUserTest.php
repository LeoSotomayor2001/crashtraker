<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('shows the registration screen', function (){
    $response= get(route('register'));

    $response->assertOk();
    $response->assertStatus(200);
});

it('register a new user as unverified and dispatches the registered event', function () {
    Event::fake();
    $response = post(route('register.store'), [
        'name' => 'Leodomi',
        'email' => 'leodomi22@hothaul.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect(route('verification.notice'));

    $user = User::where('email', '=', 'leodomi22@hothaul.com')->first();

    expect($user)->not->toBeNull();
    expect($user)->hasVerifiedEmail()->toBeFalse();

    Event::assertDispatched(Registered::class);
});

