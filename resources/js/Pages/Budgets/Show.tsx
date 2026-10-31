import AmountDisplay from "@/components/AmountDisplay";
import { ToastContainer } from 'react-toastify'
import ExpenseModal from "@/components/ExpenseModal";
import { useExpenseModalStore } from "@/stores/expense-modal-store";
import { Budget } from "@/types/budget";
import { Category } from "@/types/category";
import { Head } from '@inertiajs/react';
import { formatDate } from "@/utils";
import ProgressBar from "@/components/ProgressBar";
import { useEffect, useState } from "react";
import ExpenseDropdown from "@/components/ExpenseDropdown";
import DeleteExpenseModal from "@/components/DeleteExpenseModal";
import CashTrackrAgent from "@/components/CashTrakrAgent";


type Props = {
  budget: Budget
  categories: Category[]
  spent: string
}
export default function Show({ budget, categories, spent }: Props) {

  const openCreateModal = useExpenseModalStore(state => state.openCreateModal)
  useExpenseModalStore.getState().setBudget(budget)
  useExpenseModalStore.getState().setCategories(categories)

  const percentageUsed= +((+spent / +budget.amount) * 100).toFixed(2)
  const remaining= +budget.amount - +spent

  const [progress, setProgress] = useState(0)

  useEffect(() => {
    const timeout= setTimeout(() => {
      setProgress(percentageUsed)
    }, 150);

    return () => clearTimeout(timeout)
  }, [percentageUsed])
  
  return (
    <>
      <Head title={`Presupuesto: ${budget.name}`} />
      <section className="sm:flex sm:items-center mt-10">
        <div className="sm:flex-auto">
          <h1 className="font-bold text-4xl">Presupuesto: {budget.name}</h1>
          <p className="mt-2 text-xl text-gray-500 dark:text-gray-400">Maneja tu Presupuesto, añade, quita o edita tus gastos aquí.</p>
        </div>
        <div className="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <a
            href={'/dashboard'}
            className="block bg-amber-500 text-white w-full px-5 py-3 rounded-lg  font-bold  text-xl cursor-pointer text-center">Volver a Presupuestos</a>
        </div>
      </section>

      <main className='grid grid-cols-1 md:grid-cols-2 items-center gap-20 mt-10'>
        <ProgressBar percentageUsed={progress}></ProgressBar>
        <div className='space-y-5'>
          <AmountDisplay label="Presupuesto" amount={+budget.amount} />
          <AmountDisplay label="Gastado" amount={+spent} />
          <AmountDisplay label="Restante" amount={remaining} />
        </div>
      </main>
      <section className="mt-10 rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-200 sm:p-10 lg:px-5 dark:bg-gray-900 dark:ring-gray-800">
        <div className="flex items-center justify-between">
          <h2 className="text-3xl font-bold">Gastos</h2>
          <button
            className="bg-purple-950 hover:bg-purple-800 px-5 py-2 my-5 rounded-lg text-white font-bold text-xl cursor-pointer"
            onClick={openCreateModal}
          >
            Nuevo Gasto
          </button>
        </div>

        {budget.expenses.length ? (

          <div className="mt-8 flow-root ">
            <div className="rounded-lg bg-white ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-800">
              <div className="inline-block min-w-full align-middle">
                <table className="relative min-w-full">
                  <thead>
                    <tr>
                      <th scope="col">
                        <span className="sr-only">Gastos</span>
                      </th>
                      <th scope="col">
                        <span className="sr-only">Acciones</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-gray-200 dark:divide-gray-800">
                    {budget.expenses.map(expense => (

                      <tr key={expense.id} className='flex justify-between items-center '>
                        <td className={`${budget.type==='general' ? 'pt-10' :'pt-5'} pb-5 px-10 relative`}>
                          {budget.type === 'general' && (
                            <p className={`absolute top-0 left-0 inline-block px-3 py-1 rounded-br-2xl text-sm font-medium w-40 ${expense.category_color}`}>
                              {expense.category_label}
                            </p>

                          )}
                          <p className="text-xl font-bold text-gray-700 dark:text-gray-200">{expense.name}</p>
                          <p className="text-lg text-gray-600 dark:text-gray-300">{expense.amount}</p>
                          <p className='text-sm text-gray-500 dark:text-gray-400'>Agregado el: {formatDate(expense.created_at)}</p>
                        </td>
                        <td className="py-6 px-10 flex justify-end gap-3">
                          <ExpenseDropdown expense={expense}/>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        ) : (
          <p className="text-center text-xl mt-10 ">No Hay Gastos.
            <button
              type='button'
              onClick={openCreateModal}
              className="text-amber-500"
            >Comienza creando uno</button>
          </p>

        )}

      </section>
      <CashTrackrAgent budgetId={budget.id}/>
      <ExpenseModal />
      <DeleteExpenseModal/>
      <ToastContainer />
    </>
  )
}
