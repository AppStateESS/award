'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {ReferenceInvitation} from '../../ResourceTypes'

const ConfirmBadge = ({status}: {status: number}) => {
  switch (status) {
    case 0:
      return <span className="badge badge-info text-white">Waiting</span>
    case 1:
      return <span className="badge badge-success">Confirmed</span>
    case 2:
      return <span className="badge badge-danger">Refused</span>
  }
  return <div>Unknown</div>
}

type Props = {invitationList: ReferenceInvitation[]}

const Invitation = ({invitationList}: Props) => {
  const content = invitationList.map((value) => {
    return (
      <tr key={`invitation-${value.id}`}>
        <td>
          {value.invitedFirstName} {value.invitedLastName}
        </td>
        <td>
          <a href={`mailto:${value.email}`}>{value.email}</a>
        </td>
        <td>
          <ConfirmBadge status={value.confirm} />
        </td>
      </tr>
    )
  })
  return (
    <div>
      <table className="table table-striped">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>{content}</tbody>
      </table>
    </div>
  )
}

Invitation.propTypes = {invitationList: PropTypes.array}
export default Invitation
