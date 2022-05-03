'use strict'
import React, {useState} from 'react'
import PropTypes from 'prop-types'

const TextType = (props) => {
  const {value, update, name, allowEmpty, type, columns} = props

  const [emptyError, setEmptyError] = useState(false)
  const required = props.required || !allowEmpty
  const checkValue = () => {
    setEmptyError(!allowEmpty && value.length === 0)
  }

  let label
  if (props.label === undefined) {
    label = name[0].toUpperCase() + name.substr(1)
  }

  let input
  switch (type) {
    case 'textarea':
      input = (
        <textarea
          className="form-control"
          name={name}
          onBlur={checkValue}
          value={value}
          required
          onChange={(e) => update(e.target.value)}
        />
      )
      break
    case 'input':
    case 'text':
    case 'password':
    default:
      input = (
        <input
          type={type === 'password' ? 'password' : 'text'}
          className="form-control"
          name={name}
          onBlur={checkValue}
          value={value}
          required
          onChange={(e) => update(e.target.value)}
        />
      )
      break
  }

  return (
    <div className="form-group row">
      <label
        htmlFor={name}
        className={`col-sm-${columns[0]} col-form-label ${
          required ? 'required' : ''
        }`}>
        {label}
      </label>
      <div className={`col-sm-${columns[1]}`}>
        {input}
        {emptyError && (
          <span className="text-danger small">Cannot leave blank</span>
        )}
      </div>
    </div>
  )
}

TextType.propTypes = {
  value: PropTypes.string.isRequired,
  name: PropTypes.string,
  update: PropTypes.func.isRequired,
  label: PropTypes.string,
  allowEmpty: PropTypes.bool,
  required: PropTypes.bool,
  type: PropTypes.string.isRequired,
  columns: PropTypes.array,
}

TextType.defaultProps = {
  allowEmpty: true,
  columns: [6, 6],
}

export default TextType
