<?php

use App\Models\User;
use App\Notifications\VeryfyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('shows the registration screen', function () {
    $response = get(route('register'));

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

it('should validate require fields', function () {
    $response = post(route('register.store'), []);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
});

it('prevent duplicates email adresses', function () {

    User::factory()->create([
        'email' => 'leodomi22@hothaul.com'
    ]);

    $response = post(route('register.store'), [
        'name' => 'Leodomi',
        'email' => 'leodomi22@hothaul.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect();

    $response->assertSessionHasErrors(['email' => 'Este correo ya esta registrado',]);
});

it('should send a email to user for verification', function () {
    Notification::fake();
    $response = post(route('register.store'), [
        'name' => 'Leodomi',
        'email' => 'leodomi22@hothaul.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);


    $user = User::where('email', '=', 'leodomi22@hothaul.com')->first();

    Notification::assertSentTo($user, VeryfyEmail::class);
});

it('verificar que el email del usuario sea autenticado', function () {
    $user=User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]
    );

    $reponse = $this->actingAs($user)->get($verificationUrl);

    $reponse->assertRedirect(route('dashboard'));

    expect($user->hasVerifiedEmail())->toBe(true);
});
