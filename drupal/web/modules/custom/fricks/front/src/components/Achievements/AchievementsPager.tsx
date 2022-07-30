import React from 'react'

import { AchivementsPagerProps } from '../../types'

const AchievementsPager = ({ page, totalPages, onPageChange }: AchivementsPagerProps) => {
  return (
    <div className="achievementsPager">
        <ul>
          <li className="pagerItem first">1</li>
          <li className="pagerItem">2</li>
          <li className="pagerItem">3</li>
          <li className="pagerItem">4</li>
          <li className="pagerItem last">5</li>
        </ul>
      </div>
  )
}

export default AchievementsPager