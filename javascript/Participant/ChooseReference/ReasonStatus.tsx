'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {ReferenceResource} from '../../ResourceTypes'
import {reasonCompleted} from '../../Share/Reference'

interface ReasonStatusProps {
  reference: ReferenceResource
  reasonRequired: boolean
}

const ReasonStatus = ({reference, reasonRequired}: ReasonStatusProps) => {
  if (!reasonRequired) {
    return <span className="badge badge-success">Not required</span>
  } else if (reasonCompleted(reference)) {
    return <span className="badge badge-success">Reason submitted!</span>
  } else {
    return <span className="badge badge-danger">Reason not submitted</span>
  }
}

ReasonStatus.props = {
  reference: PropTypes.object,
  reasonRequired: PropTypes.bool,
}
export default ReasonStatus
