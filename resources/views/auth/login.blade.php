@extends('layouts.auth')

@section('title')
    Iniciar Sesion
@endsection
@section('auth-contents')
    @if (session('error'))
        <x-alert type='error' :message="session('error')"/>
    @endif

    <form class="mt-14 space-y-5" novalidate method="POST" action="{{ route('login.store') }}">
        @csrf
        <div class="flex flex-col gap-2">
            <label class="font-bold text-2xl" for="email">Email</label>

            <input id="email" type="email" placeholder="Email de Registro"
                class="w-full rounded-lg border border-gray-300 bg-white p-3 text-gray-900 placeholder:text-gray-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder:text-gray-500" name="email" value="{{ old('email') }}"
                tabindex="1" />
            <x-input-error field="email" />
        </div>

        <div class="flex flex-col gap-2">
            <div class="flex  items-center justify-between">
                <label class="font-bold text-2xl">Password</label>
                <a href="#" class="text-indigo-950 hover:underline dark:text-indigo-300" tabindex="3">¿Olvidaste tu Contraseña?</a>
            </div>
            <input type="password" placeholder="Password de Registro" class="w-full rounded-lg border border-gray-300 bg-white p-3 text-gray-900 placeholder:text-gray-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder:text-gray-500"
                name="password" tabindex="2" />
            <x-input-error field="password" />
        </div>
        <input type="submit" value='Iniciar Sesión'
            class="bg-purple-950 hover:bg-purple-800 w-full p-3 rounded-lg text-white font-bold  text-xl cursor-pointer" />
    </form>
@endsection
