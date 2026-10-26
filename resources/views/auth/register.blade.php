@extends('layouts.auth')

@section('title')
    Crear Cuenta
@endsection

@section('auth-contents')
    <form class="mt-14 space-y-5" novalidate method='POST' action={{ route('register.store') }}>
        @csrf
        <div class="space-y-2">
            <label class="font-bold text-2xl block" for="name">Nombre</label>

            <input id="name" type="text" placeholder="Tu Nombre" class="w-full rounded-lg border border-gray-300 bg-white p-3 text-gray-900 placeholder:text-gray-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder:text-gray-500"
                name="name" value="{{ old('name') }}" />
        </div>
        <x-input-error field="name" />

        <div class="space-y-2">
            <label class="font-bold text-2xl block" for="email">Email</label>

            <input id="email" type="email" placeholder="Email de Registro"
                class="w-full rounded-lg border border-gray-300 bg-white p-3 text-gray-900 placeholder:text-gray-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder:text-gray-500" name="email" value="{{ old('email') }}" />
        </div>
        <x-input-error field="email" />
        <div class="space-y-2">
            <label class="font-bold text-2xl block">Password</label>

            <input type="password" placeholder="Password de Registro" class="w-full rounded-lg border border-gray-300 bg-white p-3 text-gray-900 placeholder:text-gray-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder:text-gray-500"
                name="password" />
        </div>
        <x-input-error field="password" />

        <div class="space-y-2">
            <label class="font-bold text-2xl block" for="password_confirmation">Repetir Password</label>

            <input type="password" placeholder="Password de Registro" class="w-full rounded-lg border border-gray-300 bg-white p-3 text-gray-900 placeholder:text-gray-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder:text-gray-500"
                name="password_confirmation" />
        </div>

        <input type="submit" value='Registrarme'
            class="bg-purple-950 hover:bg-purple-800 w-full p-3 rounded-lg text-white font-bold  text-xl cursor-pointer" />
    </form>
@endsection
