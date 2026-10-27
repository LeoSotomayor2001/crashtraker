import { useExpenseModalStore } from '@/stores/expense-modal-store'
import { Expense } from '@/types/expense'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/react'

type Props={
    expense: Expense
}

export default function ExpenseDropdown({expense}: Props) {
    const openEditModal=useExpenseModalStore(state => state.openEditModal)
  return (
    <Menu as="div" className="relative inline-block text-left">
      <MenuButton className="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring-1 inset-ring-gray-300 hover:bg-gray-50 dark:bg-slate-800 dark:text-slate-200 dark:shadow-none dark:inset-ring-slate-700 dark:hover:bg-slate-700 transition-colors">
        Opciones
      </MenuButton>

      <MenuItems
        transition
        className="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg outline-1 outline-black/5 transition data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in dark:divide-slate-700 dark:bg-slate-800 dark:shadow-2xl dark:-outline-offset-1 dark:outline-slate-700"
      >
        <div className="py-1">
          <MenuItem>
            <button
              type="button"
              onClick={() => openEditModal(expense)}
              className="group flex w-full items-center px-4 py-2 text-left text-sm text-gray-700 data-focus:bg-gray-100 data-focus:text-gray-900 data-focus:outline-hidden dark:text-slate-300 dark:data-focus:bg-slate-700/60 dark:data-focus:text-white"
            >
              Editar
            </button>
          </MenuItem>

          <MenuItem>
            <button
              type="button"
              onClick={() => {}}
              className="group flex w-full items-center px-4 py-2 text-left text-sm text-red-600 data-focus:bg-gray-100 data-focus:text-red-700 data-focus:outline-hidden dark:text-red-400 dark:data-focus:bg-slate-700/60 dark:data-focus:text-red-300"
            >
              Eliminar
            </button>
          </MenuItem>
        </div>
      </MenuItems>
    </Menu>
  )
}