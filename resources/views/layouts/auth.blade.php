@extends('layouts.base')

@section('contents')
<main class="max-w-2xl mt-10 mx-auto rounded-2xl bg-white p-10 shadow-lg ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-800">
    <h1 class="font-bold text-4xl">@yield('title')</h1>
    @yield('auth-contents')
</main>

@endsection