'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import './style.css'

type Props = {text: string}
const ReferenceText = ({text}: Props) => {
  return <div className="endorsement-text">{text}</div>
}

ReferenceText.propTypes = {text: PropTypes.string}
export default ReferenceText
