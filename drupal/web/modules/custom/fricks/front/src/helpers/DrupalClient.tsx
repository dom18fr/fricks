import Axios, { AxiosResponse } from 'axios'

import { 
  DrupalClientType, 
  GetParams, 
  ExtractDrupalFieldValue, 
  DrupalFieldItemList, 
  DrupalFieldItem, 
  DrupalFieldValue, 
  DrupalFieldItemValue 
} from '../types'

const DrupalClient: DrupalClientType = ({
  get: async ({ route, queryParams }: GetParams): Promise<AxiosResponse> => Axios.request({
    baseURL: '/', //@toto: use proper env variable
    url: route,
    params: queryParams
  }),
  extract: ({ field, type, cardinality = 1 }: ExtractDrupalFieldValue): DrupalFieldValue => {
    if (!field) {

      return undefined
    }
    switch (type) {
      case 'text_long':
        return toDrupalFieldValue(field, cardinality, field => field.processed)
      case 'image':
        return toDrupalFieldValue(field, cardinality, field => {
          // @ts-ignore
          const { field_media_image: [{ url = '' } = {}] = [] } = field

          return url
        })
      default:
        return toDrupalFieldValue(field, cardinality, field => field.value)
    }
  }
})

const toDrupalFieldValue = (
  fieldItemList: DrupalFieldItemList, 
  cardinality: number, 
  toValue: (fieldItem: DrupalFieldItem) => any
) => {
  if (cardinality === 1) {

    return toValue(fieldItemList[0])
  }

  return fieldItemList.map((fieldItem: DrupalFieldItem): DrupalFieldItemValue => toValue(fieldItem))
}

export default DrupalClient