import React, { useEffect, useState } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { RootState } from 'redux/store'

import DrupalClient from '../helpers/DrupalClient'
import Loader from './Loader'
import AchievementsGrid from './AchievementsGrid'

import { fetchAchievementData } from '../redux/actions'

const Achievements = () => {

  const dispatch = useDispatch()

  const { title, achievements, isLoading } = useSelector((state: RootState) => ({
    title: DrupalClient.extract({ field: state?.staticContent?.field_achievement_title }),
    achievements: state?.achievements?.collection || [],
    isLoading: state?.achievements?.isLoading
  }))
  
  const [ filters, setFilters ] = useState({location: undefined, material: undefined})
  const [ page, setPage ] = useState(0)

  useEffect(
    () => {
      dispatch(fetchAchievementData(filters, page))
    },
    [ filters, page, dispatch ]
  )
  
  return (
    <section className="slice achievements">
      <h2 className="title-2">{ title }</h2>
      <div className="achievementsFilters" onClick={event => {
        console.log('clicked !')
      }}>clique</div>
      { isLoading && <Loader /> }
      { !isLoading && <AchievementsGrid achievements={achievements} /> }
      <div className="achievementsPager">
        <ul>
          <li className="pagerItem first">1</li>
          <li className="pagerItem">2</li>
          <li className="pagerItem">3</li>
          <li className="pagerItem">4</li>
          <li className="pagerItem last">5</li>
        </ul>
      </div>
    </section>
  )
}

export default Achievements