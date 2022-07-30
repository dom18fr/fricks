import { AxiosResponse } from 'axios'

export type AppProps = {
  drupalData: Record<any,any>
}

export type Reducer = Record<any,any>

export type RootState = {
  staticContent?: Record<any,any>
  achievements?: Record<any,any>
  yourProject?: Record<any,any>
}

export type Action = {
  type: string
  payload?: any
}

export type DrupalClientType = {
  get: (a: GetParams) => Promise<AxiosResponse>
  extract: (a: ExtractDrupalFieldValue) => DrupalFieldValue
}

export type GetParams = {
  route: string
  queryParams?: Record<string,any>
}

export type DrupalFieldItem = Record<string,string|number>

export type DrupalFieldItemList = Array<DrupalFieldItem>

export type ExtractDrupalFieldValue = {
  field: DrupalFieldItemList
  type?: string
  cardinality?: number 
}

export type DrupalFieldItemValue = string|number|undefined

export type DrupalFieldItemListValue = Array<DrupalFieldItemValue>

export type DrupalFieldValue = DrupalFieldItemValue|DrupalFieldItemListValue

export type AchivementItem = {
  title: DrupalFieldItemList
  field_main_picture: DrupalFieldItemList
}

export type AchievementsGridProps = {
  achievements: Array<AchivementItem>
}

export type AchievementsFiltersProps = {
  onFiltersChange: (filters: AchievementsFiltersType) => void
  filters: AchievementsFiltersType
}

export type AchievementsFiltersType = {
  location?: string
  material?: string
}

export type AchievementItem = {
  title: DrupalFieldItemList
  field_description: DrupalFieldItemList
  field_main_picture: DrupalFieldItemList
}

export type FilterValueDefinitionItem = {
  value?: string
  label: string
}

export type FiltersDefintionItem = {
  filter: 'location'|'material'
  values: Array<FilterValueDefinitionItem>
}

export type FiltersDefintionType = Array<FiltersDefintionItem>

export type AchivementsPagerProps = {
  page: number
  totalPages: number,
  onPageChange: (page: number) => void
}