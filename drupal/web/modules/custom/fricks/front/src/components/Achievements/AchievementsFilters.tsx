import React from 'react'
import classnames from 'classnames'

import { AchievementsFiltersProps, FiltersDefintionItem, FiltersDefintionType } from '../../types'

// @todo: find a way to filter on taxonomy term code, not tid :/

const AchievementsFilters = ({ onFiltersChange, filters }: AchievementsFiltersProps) => (
  <div className="achievementsFilters">
    {
      filtersDefinition.map(
        ({ filter, values }: FiltersDefintionItem) => (
          <div 
            key={filter}
            className={classnames('achievementFiler', filter)}
          >
            {
              values.map(({ value, label }) => (
                <label key={`${filter}-${value}`}>
                  <input 
                    type="radio" 
                    name={`achievementsFilter-${filter}`} 
                    value={value} 
                    checked={ filters[filter] === value}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => {
                      const value = event.target.value !== '' ? event.target.value : undefined
                      onFiltersChange({
                        ...filters,
                        [filter]: value
                      })
                    }}
                  />
                  <span className="achievementFilterValueLabel">{ label }</span>
                </label>
              ))
            }
          </div>
        )
      )
    }
  </div>
)

const filtersDefinition: FiltersDefintionType = [
  {
    filter: 'location',
    values: [
      {
        value: 'indoor',
        label: 'Intérieur'
      },
      {
        value: 'outdoor',
        label: 'Extérieur'
      },
      {
        value: undefined,
        label: 'Tout'
      }
    ]
  },
  {
    filter: 'material',
    values: [
      {
        value: 'wood',
        label: 'Bois',
      },
      {
        value: 'metal',
        label: 'Métal',
      },
      {
        value: undefined,
        label: 'Tout'
      }
    ]
  }
]

export default AchievementsFilters