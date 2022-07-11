'use strict'
import React, {ReactNode} from 'react'
import PropTypes from 'prop-types'

interface Props {
  columns: number[]
  required?: boolean
  children: ReactNode | ReactNode[]
  label: string
  info?: string
}
const Labeled = ({columns, required, children, label, info}: Props) => {
  return (
    <div className="form-group row">
      <label
        className={`col-sm-${columns[0]} col-form-label ${
          required ? 'required' : ''
        }`}>
        {label}
        {info && <div className="small">{info}</div>}
      </label>
      <div className={`col-sm-${columns[1]}`}>{children}</div>
    </div>
  )
}

Labeled.propTypes = {
  columns: PropTypes.array,
  info: PropTypes.string,
  input: PropTypes.node,
  label: PropTypes.string,
  required: PropTypes.bool,
}

Labeled.defaultProps = {
  columns: [6, 6],
  required: false,
}
export default Labeled
