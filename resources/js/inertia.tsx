/// <reference types="vite/client" />
import { createInertiaApp } from '@inertiajs/react'
import './theme'

createInertiaApp({
    title: title => `CashTrackr - ${title}`,
    pages: {
        path: './Pages',
        extension: '.tsx',
    },
})