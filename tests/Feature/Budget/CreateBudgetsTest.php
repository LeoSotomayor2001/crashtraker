<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('validar cuando se envia form vacio', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)
        ->from(route('budgets.create'))->post(route('budgets.store'), [
            'name' => '',
            'amount' => '',
            'type' => ''
        ]);

    $response->assertRedirect(route('budgets.create'));
    $response->assertSessionHasErrors(['name', 'amount', 'type']);
});


it('no le permite crear un budget a un usuario que no tiene sesion activa', function(){
    $response= $this->post(route('budgets.store'),[
        'name' => 'boda',
        'amount' => 222,
        'type' => 'goal'
    ]);

    $response->assertRedirect(route('login'));
});

it('un presupuesto creado se asigna al usuario autenticado', function (){
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $this->actingAs($user)->post(route('budgets.store',[
        'name' => 'boda',
        'amount' => 222,
        'type' => 'goal'
    ]));

    $budget=Budget::first();
    expect($budget->user_id)->toBe($user->id);
});


it('un presupuesto no puede ser creado por  usuario no verificado', function (){
    $user = User::factory()->create([
        'email_verified_at' => null
    ]);
     $response=$this->actingAs($user)->post(route('budgets.store',[
        'name' => 'boda',
        'amount' => 222,
        'type' => 'goal'
    ]));
    
    $response->assertRedirect(route('verification.notice'));
});