import React, { useEffect, useState } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { RootState, AchievementsFiltersType } from '../../types'

import DrupalClient from '../../helpers/DrupalClient'

import Loader from '../Loader'
import AchievementFilters from './AchievementsFilters'
import AchievementsGrid from './AchievementsGrid'
import AchievementsPager from './AchievementsPager'

import { fetchAchievementData } from '../../redux/actions'

const Achievements = () => {

  const dispatch = useDispatch()

  const { title, achievements, totalPages, isLoading } = useSelector((state: RootState) => ({
    title: DrupalClient.extract({ field: state?.staticContent?.field_achievement_title }),
    achievements: state?.achievements?.collection || [],
    totalPages: state?.achievements?.totalItems || 0,
    isLoading: state?.achievements?.isLoading
  }))
  
  const [ filters, setFilters ] = useState<AchievementsFiltersType>({location: undefined, material: undefined})
  const [ page, setPage ] = useState<number>(0)

  useEffect(
    () => {
      dispatch(fetchAchievementData(filters, page))
    },
    [ filters, page, dispatch ]
  )

  return (
    <section className="slice achievements">
      <h2 className="title-2">{ title }</h2>
      <AchievementFilters 
        filters={filters}
        onFiltersChange={setFilters}
      />
      <div className="achivementsGridWrapper">
        { isLoading && <Loader /> }
        <AchievementsGrid achievements={achievements} />
      </div>
      <AchievementsPager 
        page={page} 
        totalPages={totalPages}
        onPageChange={setPage}
      />
    </section>
  )
}

export default Achievements