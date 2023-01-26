'use strict'
import React, {useState, useEffect} from 'react'
import {
  getCycleInvitations,
  sendInvitationReminder,
} from '../../Share/InvitationXHR'
import {InvitationResource, AwardDefines} from '../../ResourceTypes'
import Loading from '../../Share/Loading'
import {createRoot} from 'react-dom/client'
import {getInviteType} from '../../Share/Invitation'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {faEnvelope} from '@fortawesome/free-solid-svg-icons'
import {invitationReminderAllowed} from '../../Share/Reminder'

declare const cycleId: number
declare const deadlinePassed: boolean

const CycleInvitationStatus = () => {
  const [invitationList, setInvitationList] = useState<InvitationResource[]>([])
  const [loading, setLoading] = useState(true)
  const [serverError, setServerError] = useState(false)

  useEffect(() => {
    load()
  }, [])

  const confirmStatus = (confirm: number) => {
    switch (confirm) {
      case 0:
        return <span className="badge badge-info text-white">No response</span>
      case 1:
        return <span className="badge badge-success">Confirmed</span>
      case 2:
        return <span className="badge badge-danger">Refused</span>
    }
  }

  const load = () => {
    setLoading(true)
    getCycleInvitations(cycleId)
      .then((response) => {
        setLoading(false)
        setInvitationList(response.data)
      })
      .catch(() => {
        setLoading(false)
        setServerError(true)
      })
  }

  const remind = (key: number) => {
    const invitation = invitationList[key]
    sendInvitationReminder(invitation.id, 'Admin').then((response) => {
      if (response.data.success) {
        invitation.lastReminder = response.data.last
        invitationList[key] = invitation
        setInvitationList([...invitationList])
      }
    })
  }

  let content = <Loading things="invitations" />
  if (serverError) {
    content = <div className="alert alert-danger">Server error</div>
  } else if (!loading) {
    if (invitationList.length > 0) {
      const rows = invitationList?.map((value, key) => {
        let remindButton
        if (value.confirm === AwardDefines.INVITATION_WAITING) {
          if (deadlinePassed) {
            remindButton = (
              <span
                className="badge badge-warning text-white"
                title={`Last sent ${value.lastReminder}`}>
                Deadline passed
              </span>
            )
          } else if (
            invitationReminderAllowed(value.lastReminder, value.inviteType)
          ) {
            remindButton = (
              <span
                className="badge badge-primary"
                style={{cursor: 'pointer'}}
                onClick={() => {
                  remind(key)
                }}>
                Send reminder
              </span>
            )
          } else {
            remindButton = (
              <span
                className="badge badge-info text-white"
                title={`Last sent ${value.lastReminder}`}>
                Too soon
              </span>
            )
          }
        }
        return (
          <tr key={`invite-${value.id}`}>
            <td>
              {value.invitedFirstName} {value.invitedLastName}{' '}
              <sup>
                <a href={`mailto:${value.invitedEmail}`}>
                  <FontAwesomeIcon icon={faEnvelope} />
                </a>
              </sup>
            </td>
            <td>
              {getInviteType(value.inviteType)}{' '}
              {value.inviteType === AwardDefines.INVITE_TYPE_REFERENCE &&
                `for ${value.nominatedFirstName} ${value.nominatedLastName}`}
            </td>
            <td>{confirmStatus(value.confirm)}</td>
            <td>{remindButton}</td>
          </tr>
        )
      })
      content = (
        <table className="table">
          <thead>
            <tr>
              <th>Invited</th>
              <th>Invite type</th>
              <th>Status</th>
              <th>Remind</th>
            </tr>
          </thead>
          <tbody>{rows}</tbody>
        </table>
      )
    } else {
      content = (
        <div>
          <em>No pending invitations.</em>
        </div>
      )
    }
  }

  return (
    <div>
      <div className="card">
        <div className="card-header p-2">
          <h4 className="m-0">Invitation status</h4>
        </div>
        <div className="card-body">{content}</div>
      </div>
    </div>
  )
}

const container = document.getElementById(
  'CycleInvitationStatus'
) as HTMLElement
const root = createRoot(container)
root.render(<CycleInvitationStatus />)
