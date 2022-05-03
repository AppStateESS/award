'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'

const Checkbox = ({label, update, value, columns}) => {
  const textColorClass = value ? 'true-check' : 'false-check'
  const checkIcon = (
    <div
      className={`checkbox-container ${textColorClass}`}
      onClick={() => update(!value)}>
      <FontAwesomeIcon
        icon={['fas', value ? 'fa-check-square' : 'fa-square']}
        size="2x"
      />
      <span className="check-label">{value ? 'Yes' : 'No'}</span>
    </div>
  )

  return (
    <div className="form-group row">
      <div className={`col-sm-${columns[0]}`}>{label}</div>
      <div className={`col-sm-${columns[1]}`}>{checkIcon}</div>
    </div>
  )
}

Checkbox.propTypes = {
  label: PropTypes.string,
  update: PropTypes.func.isRequired,
  value: PropTypes.bool,
  columns: PropTypes.array,
}

Checkbox.defaultProps = {
  columns: [6, 6],
}
export default Checkbox
