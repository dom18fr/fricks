import React from 'react'

import { AchievementsGridProps, AchivementItem } from '../../types'

import DrupalClient from '../../helpers/DrupalClient'

const AchievementsGrid = ({ achievements }: AchievementsGridProps) =>  (
  <>
    {
      achievements.map(
        ({ title, field_main_picture }: AchivementItem, index: number) => (
          <ul key={index} className="achievementsGrid">
            <li className="achievementTeaser">
              <img 
                width="100" // @todo: remove it
                src={ DrupalClient.extract({field: field_main_picture, type: 'image'})?.toString() } 
                className="achievementTeaserImage"
              />
              <h3 className="achievementTeaserName title-3">{ DrupalClient.extract({field: title}) }</h3>
            </li>
          </ul>
        )
      )
    }
  </>
)

export default AchievementsGrid