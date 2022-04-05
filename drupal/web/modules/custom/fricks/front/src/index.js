import React from 'react'
import { createRoot } from 'react-dom/client';
import App from './App'

const root = document.querySelector('[data-react=fricks]')
createRoot(root).render(<App />)
