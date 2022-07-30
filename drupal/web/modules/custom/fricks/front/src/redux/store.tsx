import { applyMiddleware, combineReducers, compose, createStore } from 'redux'
import thunk from 'redux-thunk'

import { Action, Reducer, RootState } from '../types'

export const types = {
  SET_STATIC_CONTENT_DATA: 'SET_STATIC_CONTENT_DATA',
  SET_ACHIEVEMENTS_COLLECTION: 'SET_ACHIEVEMENTS_COLLECTION',
  SET_ACHIEVEMENTS_TOTAL_ITEMS: 'SET_ACHIEVEMENTS_TOTAL_ITEMS',
  SET_ACHIEVEMENTS_IS_LOADING: 'SET_ACHIEVEMENTS_IS_LOADING',
  SET_YOUR_PROJECT_DATA: 'SET_YOUR_PROJECT_DATA',
}

const initialStaticContentState = {}
const initialAchievementState = {}
const initialYourProjectState = {}

const staticContent = (
  state: Reducer = initialStaticContentState, 
  { type, payload }: Action
): Reducer => {

  switch (type) {
    case types.SET_STATIC_CONTENT_DATA:
      return {
        ...state,
        ...payload
      } 
    default:
      return state
  }
}

const achievements = (
  state: Reducer = initialAchievementState, 
  { type, payload }: Action
): Reducer => {
  switch (type) {
    case types.SET_ACHIEVEMENTS_IS_LOADING:
      return {
        ...state,
        isLoading: payload
      }
    case types.SET_ACHIEVEMENTS_COLLECTION:
      return {
        ...state,
        collection: payload
      }
    case types.SET_ACHIEVEMENTS_TOTAL_ITEMS:
      return {
        ...state,
        totalItems: payload
      }
    default:
      return state
  }
}

const yourProject = (
  state: Reducer = initialYourProjectState, 
  { type, payload }: Action
): Reducer => {
  switch (type) {
    default:
      return state
  }
}

const fricksReducer: Reducer = combineReducers({
  staticContent,
  achievements,
  yourProject
})
// @ts-ignore
const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose
// @ts-ignore
const store = createStore(fricksReducer, composeEnhancers(applyMiddleware(thunk)))

export default store
