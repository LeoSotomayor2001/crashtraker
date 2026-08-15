<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\from;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('logs in a verified user succesfully', function (){
    User::factory()->create([
        'email' => 'Leo@leo.com',
        'password'=> bcrypt('Password123!'),
        'email_verified_at' => now() 
    ]);
     $response=post(route('login.store'),[
        'email' => 'Leo@leo.com',
        'password' => 'Password123!'
     ]);

     $response->assertRedirect(route('dashboard'));
     $this->assertAuthenticated();
});


it('no se logea si todo esta mal', function (){
    User::factory()->create([
        'email' => 'Leo@leo.com',
        'password'=> bcrypt('Password123!'),
    ]);
     $response=from(route('login'))->post(route('login.store'),[
        'email' => 'Leo@leo.com',
        'password' => 'aasdPassword123!'
     ]);


    $response->assertRedirect(route('login'));
    $response->assertSessionHas('error', 'Credenciales Incorrectas.');

    $this->assertGuest();

});

it('previene que los usuarios no verificados entren', function (){
    User::factory()->unverified()->create([
        'email' => 'Leo@leo.com',
        'password'=> bcrypt('Password123!'),
    ]);
     $response=post(route('login.store'),[
        'email' => 'Leo@leo.com',
        'password' => 'Password123!'
     ]);

     $response->assertRedirect(route('dashboard'));
     $this->assertAuthenticated();

     $dashboardResponse=get(route('dashboard'));
     $dashboardResponse->assertRedirect(route('verification.notice'));
});