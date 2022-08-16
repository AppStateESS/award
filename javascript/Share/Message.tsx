'use strict'
import React from 'react'
import PropTypes from 'prop-types'

export interface MessageType {
  message: string
  type: string
}

type Props = {
  message: MessageType
}
export const Message = ({message}: Props) => {
  if (message.message.length > 0) {
    return (
      <div className={`alert alert-${message.type}`}>{message.message}</div>
    )
  } else {
    return <span></span>
  }
}

Message.propTypes = {message: PropTypes.object}
