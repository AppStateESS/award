'use strict'
import React from 'react'
import PropTypes from 'prop-types'

const randomKey = () => (Math.random() + 1).toString(36).substring(7)

const Select = ({name, label, update, options, value, columns}) => {
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
    <div className="form-group row">
      <label htmlFor={name} className={`col-sm-${columns[0]} col-form-label`}>
        {label}
      </label>
      <div className={`col-sm-${columns[1]}`}>
        <select
          name={name}
          value={value}
          className="form-control"
          defaultChecked={value}
          onChange={(e) => update(e.target.value)}>
          {mappedOptions}
        </select>
      </div>
    </div>
  )
}

Select.propTypes = {
  name: PropTypes.string,
  label: PropTypes.string,
  update: PropTypes.func.isRequired,
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
  columns: PropTypes.array,
  options: PropTypes.array.isRequired,
}

Select.defaultProps = {
  columns: [6, 6],
}
export default Select
