'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'

const Loading = ({things}) => {
  return (
    <p className="lead text-center">
      <FontAwesomeIcon icon={['fa', 'spinner']} spin /> Loading {things}...
    </p>
  )
}

Loading.defaultProps = {things: ''}

Loading.propTypes = {things: PropTypes.string}
export default Loading
