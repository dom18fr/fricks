import { types } from './store'
import DrupalClient from '../helpers/DrupalClient'
import { Dispatch } from 'react'

import { Action, AchievementsFiltersType, AchievementItem } from '../types'

export const loadStaticContent = (drupalData: Record<any,any>): Action => ({
  type: types.SET_STATIC_CONTENT_DATA,
  payload: drupalData
})

export const fetchAchievementData = (filters: AchievementsFiltersType, page: number, itemsPerPage = 9) => async (dispatch: Dispatch<Action>) => {
  dispatch(setAchivementsIsLoading())
  try {
    const { data: { collection = [], totalItems } = {} } = await DrupalClient.get({ 
      route: '/api/entity/view/achievements/api.api', 
      queryParams: { ...filters, page, itemsPerPage }
    })
    dispatch(setAchivementsCollection(collection))
    dispatch(setAchivementTotalItems(totalItems))
    dispatch(setAchivementsIsLoading(false))
  } catch (error) {
    console.error(error)
  }
}

const setAchivementsCollection = (collection: Array<AchievementItem> = []): Action => ({
  type: types.SET_ACHIEVEMENTS_COLLECTION,
  payload: collection
})

const setAchivementTotalItems = (totalItems: number): Action => ({
  type: types.SET_ACHIEVEMENTS_TOTAL_ITEMS,
  payload: totalItems
})

const setAchivementsIsLoading = (isLoading: boolean = true): Action => ({
  type: types.SET_ACHIEVEMENTS_IS_LOADING,
  payload: isLoading
})