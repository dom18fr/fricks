import React, { useEffect } from 'react'
import { useDispatch } from 'react-redux'

import { AppProps } from './types'

import Header from './components/Header'
import Achievements from './components/Achievements/Achievements'
import YourProject from './components/YourProject'
import Contact from './components/Contact'

import { loadStaticContent } from './redux/actions'

const App = ({ drupalData }: AppProps) => {

  const dispatch = useDispatch()
  
  useEffect(() => {
    dispatch(loadStaticContent(drupalData))
  }, [])
  
  return (
    <main>
      <Header />
      <Achievements />
      <YourProject />
      <Contact />
    </main>
  )
}

export default App
