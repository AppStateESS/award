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
      <div className={`col-sm-${columns[0]} col-form-label`}>
        <label className={`${required ? 'required' : ''}`}>{label}</label>
        {info && <div className="small">{info}</div>}
      </div>
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
