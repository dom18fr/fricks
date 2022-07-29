import { types } from './store'

export const loadStaticContent = (drupalData: Record<any,any>) => ({
  type: types.SET_STATIC_CONTENT_DATA,
  payload: drupalData
})