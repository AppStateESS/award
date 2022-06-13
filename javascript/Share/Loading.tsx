'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'

const Loading = ({things}: {things: string}) => {
  return (
    <p className="lead text-center">
      <FontAwesomeIcon icon="spinner" spin /> Loading {things}...
    </p>
  )
}

Loading.defaultProps = {things: ''}

Loading.propTypes = {things: PropTypes.string}
export default Loading
