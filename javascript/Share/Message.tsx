'use strict'
import React from 'react'
import PropTypes from 'prop-types'

type Props = {
  message: string
  type: string
}
const Message = ({message, type}: Props) => {
  if (message.length > 0) {
    return <div className={`alert alert-${type}`}>{message}</div>
  } else {
    return <span></span>
  }
}

Message.propTypes = {message: PropTypes.string, type: PropTypes.string}
Message.defaultProps = {type: 'danger'}

export default Message
