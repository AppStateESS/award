'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'

interface Props {
  update: (value: boolean) => void
  value: boolean | number
}

const Checkbox = ({update, value}: Props) => {
  const textColorClass = !!value ? 'true-check' : 'false-check'
  const icon = !!value ? 'check-square' : 'square'
  return (
    <div
      className={`checkbox-container ${textColorClass}`}
      onClick={() => update(!value)}>
      <FontAwesomeIcon icon={icon} size="2x" />
      <span className="check-label">{value ? 'Yes' : 'No'}</span>
    </div>
  )
}

Checkbox.propTypes = {
  update: PropTypes.func.isRequired,
  value: PropTypes.bool,
}

export default Checkbox
