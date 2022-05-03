'use strict'
import React, {useState, useEffect} from 'react'
import PropTypes from 'prop-types'

const randomKey = () => (Math.random() + 1).toString(36).substring(7)

const ButtonGroup = ({options, value, update, buttonClass}) => {
  const keyRoot = randomKey()
  const buttonOptions = options.map((option, count) => {
    const active = option.value === value
    return (
      <button
        key={keyRoot + count}
        type="button"
        onClick={() => update(option.value)}
        className={`btn btn-${buttonClass} ${active ? 'active' : null}`}>
        {option.label}
      </button>
    )
  })
  return (
    <div className="btn-group mr-2" role="group" aria-label="First group">
      {buttonOptions}
    </div>
  )
}

ButtonGroup.propTypes = {
  options: PropTypes.array.isRequired,
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  update: PropTypes.func,
  buttonClass: PropTypes.string,
}

ButtonGroup.defaultProps = {
  buttonClass: 'outline-secondary',
}
export default ButtonGroup
