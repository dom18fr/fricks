import { types } from './store'
import DrupalClient from '../helpers/DrupalClient'
import { Dispatch } from 'react'

import { DrupalFieldItemList } from '../helpers/DrupalClient'

export type Action = {
  type: string
  payload?: any
}

type AchievementFilters = {
  location?: string
  material?: string
}

type AchievementItem = {
  title: DrupalFieldItemList
  field_description: DrupalFieldItemList
  field_main_picture: DrupalFieldItemList
}

export const loadStaticContent = (drupalData: Record<any,any>): Action => ({
  type: types.SET_STATIC_CONTENT_DATA,
  payload: drupalData
})

export const fetchAchievementData = (filters: AchievementFilters, page: number) => async (dispatch: Dispatch<Action>) => {
  dispatch(setAchivementsIsLoading())
  try {
    const { data: { collection = [] } = {} } = await DrupalClient.get({ 
      route: '/api/entity/view/achievements/api.api', 
      queryParams: { filters, page } 
    })
    dispatch(setAchivementsCollection(collection))
    dispatch(setAchivementsIsLoading(false))
  } catch {

  } 
}

const setAchivementsCollection = (collection: Array<AchievementItem> = []): Action => ({
  type: types.SET_ACHIEVEMENTS_COLLECTION,
  payload: collection
})

const setAchivementsIsLoading = (isLoading: boolean = true): Action => ({
  type: types.SET_ACHIEVEMENTS_IS_LOADING,
  payload: isLoading
})