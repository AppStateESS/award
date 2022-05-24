'use strict'
import React from 'react'
import PropTypes from 'prop-types'

const randomKey = () => (Math.random() + 1).toString(36).substring(7)

const Select = ({name, update, options, value}) => {
  const keyRoot = randomKey()
  const mappedOptions = options.map((optionValue, count) => {
    if (typeof optionValue === 'object') {
      return (
        <option key={keyRoot + count} value={optionValue.value}>
          {optionValue.label}
        </option>
      )
    } else {
      return <option key={keyRoot + count}>{optionValue}</option>
    }
  })

  return (
    <select
      name={name}
      value={value}
      className="form-control mb-1"
      defaultChecked={value}
      onChange={(e) => update(e.target.value)}>
      {mappedOptions}
    </select>
  )
}

Select.propTypes = {
  name: PropTypes.string,
  update: PropTypes.func.isRequired,
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
  options: PropTypes.array.isRequired,
  under: PropTypes.string,
}

Select.defaultProps = {
  columns: [6, 6],
}
export default Select
