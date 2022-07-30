import React from 'react'
import classnames from 'classnames'

import { AchivementsPagerProps } from '../../types'

const AchievementsPager = ({ page, totalItems, itemsPerPage, onPageChange }: AchivementsPagerProps) => {
  
  const pages = Array.from(Array(Math.ceil(totalItems / itemsPerPage)).keys())
  const pager = [
    { type: 'button', label: '<<', pageIndex: 0, disabled: page === 0 },
    { type: 'button', label: '<', pageIndex: pages.includes(page - 1) ? page - 1 : 0, disabled: !pages.includes(page - 1) },
    { type: 'span', label: `${page + 1} sur ${pages.length}` },
    { type: 'button', label: '>', pageIndex: pages.includes(page + 1) ? page + 1 : 0, disabled: !pages.includes(page + 1) },
    { type: 'button', label: '>>', pageIndex: pages.length - 1, disabled: page === pages.length - 1 },
  ]

  return (
    <div className="achievementsPager">
      <ul>
        {
          pager.map(
            ({ type, label, pageIndex = 0, disabled }, index) => (
              <li 
                key={index} 
                className="pagerItem"
              >
                { type === 'button' && (
                  <button
                    disabled={disabled}
                    onClick={(event: React.MouseEvent<HTMLButtonElement>) => {
                      onPageChange(pageIndex)
                    }}
                  >
                    { label }
                  </button>
                )}
                { type === 'span' && (
                  <span>{ label }</span>
                )}
              </li>
            )
          )
        }
      </ul>
    </div>
  )
}

export default AchievementsPager