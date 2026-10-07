import { useExpenseModalStore } from "@/stores/expense-modal-store";
import { useForm } from "@inertiajs/react";
import { SubmitEvent } from "react";
import { toast } from "react-toastify";
import Ziggy from '@/ziggy'
import { route } from 'ziggy-js'
import InputError from "./InputError";


export default function ExpenseForm() {
    // const budget= useExpenseModalStore(state => state.budget);
    // const categories= useExpenseModalStore(state => state.categories);
    const { budget, categories } = useExpenseModalStore(state => state)
    const closeModal = useExpenseModalStore(state => state.closeModal)
    const { data, setData, post, errors, reset, processing } = useForm({
        name: '',
        amount: '',
        category: ''
    })

    const submit = (e: SubmitEvent<HTMLFormElement>) => {
        e.preventDefault()
        post(route('expenses.store', budget?.id), {
            onSuccess: (page) => {
                const success = page.props.flash?.success
                if (success) toast.success(success)
                closeModal()
                reset()
            }
        })

    }
    return (
        <div className="p-4 sm:p-10 flex justify-center">
            <form onSubmit={submit} className="flex flex-col space-y-6 w-full max-w-lg bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-2xl shadow-xl border border-slate-200/80 dark:border-slate-800 transition-colors">

                <div className="space-y-2">
                    <label
                        htmlFor="name"
                        className="block text-sm sm:text-base font-semibold text-slate-800 dark:text-slate-200"
                    >
                        Nombre del gasto
                    </label>
                    <input
                        id="name"
                        type="text"
                        value={data.name}
                        onChange={e => setData('name', e.target.value)}
                        placeholder="Ej. Compras del supermercado"
                        className="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 border border-slate-300 dark:border-slate-700 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition-all"
                    />
                    {errors.name && <InputError>{errors.name}</InputError>}
                </div>

                <div className="space-y-2">
                    <label
                        htmlFor="amount"
                        className="block text-sm sm:text-base font-semibold text-slate-800 dark:text-slate-200"
                    >
                        Cantidad del gasto
                    </label>
                    <input
                        id="amount"
                        type="number"
                        min="0"
                        value={data.amount}
                        onChange={e => setData('amount', e.target.value)}
                        step="0.01"
                        placeholder="0.00"
                        className="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 border border-slate-300 dark:border-slate-700 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition-all"
                    />
                    {errors.amount && <InputError>{errors.amount}</InputError>}
                </div>
                {budget?.type === 'general' && (
                    <div className='space-y-3'>
                        <label
                            htmlFor="category"
                            className="block text-sm sm:text-base font-semibold text-slate-800 dark:text-slate-200"
                        >
                            Categoría Gasto
                        </label>
                        <select
                            name="category"
                            id="category"
                            value={data.category}
                            onChange={e => setData('category', e.target.value)}
                            className='w-full border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-colors'
                        >
                            <option value="" className="bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                                Selecciona Categoría
                            </option>
                            {categories.map((category) => (
                                <option
                                    key={category.value}
                                    value={category.value}
                                    className="bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100"
                                >
                                    {category.label}
                                </option>
                            ))}
                        </select>
                        {errors.category && <InputError>{errors.category}</InputError>}
                    </div>
                )}
                <button
                    disabled={processing}
                    type="submit"
                    className={`${processing ? 'bg-purple-950 opacity-60 cursor-not-allowed': 'bg-purple-700 hover:bg-purple-800 active:bg-purple-900 cursor-pointer'} mt-2  text-white font-bold py-3.5 px-4 rounded-xl shadow-lg 
                    shadow-purple-700/25 transition-all 
                    duration-200  text-center`}
                >
                    {processing ? 'Guardando' : 'Agregar Gasto'}
                </button>

            </form>
        </div>
    );
}