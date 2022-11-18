'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {ReferenceResource} from '../../ResourceTypes'

import ReasonStatus from './ReasonStatus'
import RemindIcon from './RemindIcon'
import {reasonCompleted} from '../../Share/Reference'
import {sendReferenceReasonReminder} from '../../Share/ReferenceXHR'

interface CurrentProps {
  referenceList: ReferenceResource[]
  referenceReasonRequired: boolean
  reminderGrace: number
}

const sendReminder = (referenceId: number) => {
  sendReferenceReasonReminder(referenceId).then((response) => {
    console.log(response.data)
  })
}

const Current = ({
  referenceList,
  referenceReasonRequired,
  reminderGrace,
}: CurrentProps) => {
  const content = referenceList.map((value) => {
    return (
      <tr key={`reference-${value.id}`}>
        <td>
          {value.firstName} {value.lastName}
        </td>
        <td>
          <a href={`mailto:${value.email}`}>{value.email}</a>
        </td>
        <td>
          <ReasonStatus
            reference={value}
            reasonRequired={referenceReasonRequired}
          />
        </td>
        <td>
          {reasonCompleted(value) ? (
            <span />
          ) : (
            <RemindIcon
              reference={value}
              reminderGrace={reminderGrace}
              sendReminder={() => sendReminder(value.id)}
            />
          )}
        </td>
      </tr>
    )
  })
  return (
    <table className="table table-striped">
      <tbody>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Reason complete</th>
          <th>Send Reminder</th>
        </tr>
        {content}
      </tbody>
    </table>
  )
}

Current.propTypes = {
  referenceList: PropTypes.array,
  referenceReasonRequired: PropTypes.bool,
  reminderGrace: PropTypes.number,
}
export default Current
