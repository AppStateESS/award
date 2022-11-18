'use strict'
import React from 'react'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {faBell} from '@fortawesome/free-solid-svg-icons'
import PropTypes from 'prop-types'
import {ReferenceResource} from '../../ResourceTypes'

type Props = {
  reference: ReferenceResource
  reminderGrace: number
  sendReminder: VoidFunction
}
const RemindIcon = ({reference, reminderGrace, sendReminder}: Props) => {
  const lastReminder = new Date(reference.lastReminder)
  lastReminder.setDate(lastReminder.getDate() + reminderGrace)
  const now = new Date()
  if (lastReminder < now) {
    return (
      <button
        className="btn btn-success btn-sm"
        onClick={sendReminder}
        title={`Remind ${reference.firstName} to submit`}>
        <FontAwesomeIcon icon={faBell} /> Send email reminder
      </button>
    )
  } else {
    return (
      <button
        className="btn btn-danger btn-sm"
        disabled
        title="Too soon to remind again">
        <FontAwesomeIcon icon={faBell} /> Recently reminded
      </button>
    )
  }
}

RemindIcon.propTypes = {
  reference: PropTypes.object,
  reminderGrace: PropTypes.number,
}
export default RemindIcon
