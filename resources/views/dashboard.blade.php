@extends('layouts.app')

@section('title')
    Administra tus presupuestos
@endsection

@section('actions')
    <div class="sm:flex sm:items-center mt-10">
        <div class="sm:flex-auto">
            <h1 class="font-bold text-4xl">Administra tus Presupuestos</h1>
            <p class="mt-2 text-xl text-gray-500 dark:text-gray-400">Administra tus Presupuestos en esta sección</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('budgets.create') }}"
                class="block bg-amber-500 text-white w-full px-5 py-3 rounded-lg  font-bold  text-xl cursor-pointer text-center">Nuevo
                Presupuesto</a>
        </div>
    </div>
@endsection

@section('dashboard-contents')
    @if (count($budgets) > 0)
        <div class="mt-8 flow-root ">
            <div class="overflow-x-auto rounded-lg bg-white ring-1 ring-gray-200 shadow-sm dark:bg-gray-900 dark:ring-gray-800">
                <div class="inline-block min-w-full align-middle">
                    <table class="relative min-w-full">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <span class="sr-only">Presupuestos</span>
                                </th>

                                <th scope="col">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach ($budgets as $budget)
                                <tr class="flex items-center justify-between">
                                    <td class="pt-10 pb-5 px-10 relative">
                                        <p
                                            class="absolute top-0 left-0 inline-block w-40 rounded-br-2xl px-3 py-1 text-sm font-medium
                                        {{ $budget->isGeneral() ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-200' : 'bg-orange-100 text-orange-800 dark:bg-orange-950 dark:text-orange-200' }}">
                                            {{ $budget->isGeneral() ? 'General' : 'Proyecto' }}
                                        </p>
                                        <a class="block text-2xl font-bold text-gray-700 transition hover:text-purple-800 dark:text-gray-200 dark:hover:text-purple-300"
                                            href="{{ route('budgets.show',$budget) }}">{{ $budget->name }}</a>
                                        <p class="text-lg text-gray-500 dark:text-gray-400">${{ $budget->amount }}</p>
                                    </td>
                                    <td class="py-6 px-10 flex justify-end gap-3">
                                        <x-budget-dropdown :budget="$budget" />
                                        <x-confirm-delete :id="'delete-dialog-' . $budget->id" title="Eliminar Presupuesto" message="Esto no se puede deshacer"
                                            :action="route('budget.destroy',$budget)" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <p class="text-center text-xl mt-10 ">No Hay Presupuestos.
            <a href="{{ route('budgets.create') }}" class="text-amber-500">Comienza creando uno</a>
        </p>
    @endif
@endsection
