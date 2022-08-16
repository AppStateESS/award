'use strict'
import React, {useState, Fragment} from 'react'
import PropTypes from 'prop-types'

interface TextTypeProps {
  value: string
  update: (value: string | number | boolean) => void
  name?: string
  allowEmpty?: boolean
  type: string
  placeholder?: string
}

const TextType = ({
  value,
  update,
  name,
  allowEmpty,
  type,
  placeholder,
}: TextTypeProps) => {
  const [emptyError, setEmptyError] = useState(false)
  const checkValue = () => {
    setEmptyError(!allowEmpty && value.length === 0)
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
          placeholder={placeholder}
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
    <Fragment>
      {input}
      {emptyError && (
        <span className="text-danger small">Cannot leave blank</span>
      )}
    </Fragment>
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
}

TextType.defaultProps = {
  allowEmpty: true,
}

export default TextType
