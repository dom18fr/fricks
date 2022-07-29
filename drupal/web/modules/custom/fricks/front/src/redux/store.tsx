import { applyMiddleware, combineReducers, compose, createStore } from 'redux'
import thunk from 'redux-thunk'

type ActionTypes = {
  SET_STATIC_CONTENT_DATA: string
  SET_ACHIEVEMENTS_DATA: string
  SET_YOUR_PROJECT_DATA: string
}

type Action = {
  type: keyof ActionTypes
  payload?: Record<any,any>
}

type Reducer = Record<any,any>

export type RootState = {
  staticContent?: Record<any,any>
  achievements?: Record<any,any>
  yourProject?: Record<any,any>
}

export const types: ActionTypes = {
  SET_STATIC_CONTENT_DATA: 'SET_STATIC_CONTENT_DATA',
  SET_ACHIEVEMENTS_DATA: 'SET_ACHIEVEMENTS_DATA',
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
