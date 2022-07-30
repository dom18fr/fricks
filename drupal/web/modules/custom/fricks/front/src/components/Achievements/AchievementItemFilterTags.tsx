import React from 'react'
import classnames from 'classnames'

import { AchievementItemFilterTagsProps } from '../../types'

import DrupalClient from '../../helpers/DrupalClient'

const AchievementItemFilterTags = ({ location, material }: AchievementItemFilterTagsProps) => (
  <div className="achievemenCatergoryTagsWrapper">
    {
      [{ name: 'location', tags: location }, { name: 'material', tags: material }].map(
        ({ name, tags }) => tags.map(
          (tag, index) => (
            <span
              key={`${name}-${index}`} 
              className={classnames(
                'achievemenCatergoryTag', 
                name,
                DrupalClient.extract({ field: tag.field_machine_name })
              )}
            >
              { DrupalClient.extract({ field: tag.name }) }
            </span>
          )
        )
      )
    }
  </div>
)

export default AchievementItemFilterTags