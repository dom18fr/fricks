import React from 'react'
import { createRoot } from 'react-dom/client'
import { Provider } from 'react-redux'

import App from './App'
import store from './redux/store'

createRoot(document.querySelector('[data-react=fricks]')).render(
  <Provider store={store}>
    <App
      drupalData={window.drupalSettings.fricks}
    />
  </Provider>
)
