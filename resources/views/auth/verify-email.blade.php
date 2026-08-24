@extends('layouts.auth')

@section('title')
    Verificar Email
@endsection

@section('auth-contents')
    <p class="mt-5 text-lg">Tu cuenta fue creada con exito. Ahora sole debes confirmarla, revisa tu email.</p>

    <form method="POST" action="{{ route('verification.send') }}">
        <input type="submit" value="Reenviar correo de verificacion"
            class='bg-amber-500 w-full text-center mt-5 px-5 py-2 uppercase font-bold cursor-pointer'>
    </form>
@endsection
