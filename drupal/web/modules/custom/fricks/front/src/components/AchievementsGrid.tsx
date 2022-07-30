import React from 'react'
import DrupalClient, { DrupalFieldItemList } from '../helpers/DrupalClient'

type AchivementItem = {
  title: DrupalFieldItemList
  field_main_picture: DrupalFieldItemList
}

type AchievementsGridProps = {
  achievements: Array<AchivementItem>
}

const AchievementsGrid = ({ achievements }: AchievementsGridProps) =>  (
  <>
    {
      achievements.map(
        ({ title, field_main_picture }: AchivementItem, index: number) => (
          <ul key={index} className="achievementsGrid">
            <li className="achievementTeaser">
              <img src={ DrupalClient.extract({field: field_main_picture, type: 'image'})?.toString() } className="achievementTeaserImage"/>
              <h3 className="achievementTeaserName title-3">{ DrupalClient.extract({field: title}) }</h3>
            </li>
          </ul>
        )
      )
    }
  </>
)

export default AchievementsGrid