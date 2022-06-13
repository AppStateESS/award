'use strict'
import React from 'react'
import PropTypes from 'prop-types'

const randomKey = () => (Math.random() + 1).toString(36).substring(7)

interface Props {
  name?: string
  update: (value: any) => void
  options: Array<
    {value: string | number; label: string | number} | number | string
  >
  value: any
  disabled?: boolean
}

const Select = ({name, update, options, value, disabled}: Props) => {
  const keyRoot = randomKey()
  const mappedOptions = options.map((optionValue, count) => {
    if (typeof optionValue === 'object') {
      return (
        <option
          key={keyRoot + count}
          value={optionValue.value}
          disabled={disabled}>
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
  disabled: PropTypes.bool,
}

Select.defaultProps = {
  disabled: false,
}

export default Select
