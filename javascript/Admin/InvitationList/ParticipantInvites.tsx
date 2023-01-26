'use strict'
import React from 'react'
import {InvitationResource} from '../../ResourceTypes'

const ParticipantInvites = ({listing}: {listing: InvitationResource[]}) => {
  return (
    <table className="table table-striped">
      <tbody>
        <tr>
          <th>Email</th>
          <th>Date sent</th>
          <th>Date responded</th>
        </tr>
        {listing.map((value) => {
          return (
            <tr key={`invite-${value.id}`}>
              <td>
                <a href={`mailto:${value.email}`}>{value.email}</a>
              </td>
              <td>{value.created}</td>
              <td>{value.updated}</td>
            </tr>
          )
        })}
      </tbody>
    </table>
  )
}

export default ParticipantInvites
