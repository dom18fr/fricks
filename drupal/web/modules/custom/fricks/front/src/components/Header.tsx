import React from 'react'
import { useSelector } from 'react-redux'

import { RootState } from '../types'

import DrupalClient from '../helpers/DrupalClient'

const Header = () => {

  const { title, description, imageUrl } = useSelector(
    (state: RootState) => ({
      title: DrupalClient.extract({ field: state?.staticContent?.title }),
      description: DrupalClient.extract({ field: state?.staticContent?.field_main_text, type: 'text_long' }) || '',
      imageUrl: DrupalClient.extract({ field: state?.staticContent?.field_main_image, type: 'image' }) || ''
    })
  )
  
  return (
    <header className="mainHeader slice">
      <h1 className="mainTitle title-1">{ title }</h1>
      <div
        className="mainDescription is-contrib" 
        dangerouslySetInnerHTML={{ __html: description.toString() }}
      />
      <img src={imageUrl.toString()} className="mainImage"/>
    </header>
  )
}

export default Header