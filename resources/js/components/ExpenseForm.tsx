import { useExpenseModalStore } from "@/stores/expense-modal-store";

export default function ExpenseForm() {
    // const budget= useExpenseModalStore(state => state.budget);
    // const categories= useExpenseModalStore(state => state.categories);
    const { budget, categories } = useExpenseModalStore(state => state)
    return (
        <div className="p-4 sm:p-10 flex justify-center">
            <form className="flex flex-col space-y-6 w-full max-w-lg bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-2xl shadow-xl border border-slate-200/80 dark:border-slate-800 transition-colors">

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
                        placeholder="Ej. Compras del supermercado"
                        className="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 border border-slate-300 dark:border-slate-700 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition-all"
                    />
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
                        step="0.01"
                        placeholder="0.00"
                        className="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 border border-slate-300 dark:border-slate-700 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition-all"
                    />
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
                    </div>
                )}
                <button
                    type="submit"
                    className="mt-2 bg-purple-700 hover:bg-purple-800 active:bg-purple-900 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-purple-700/25 transition-all duration-200 cursor-pointer text-center"
                >
                    Agregar Gasto
                </button>

            </form>
        </div>
    );
}