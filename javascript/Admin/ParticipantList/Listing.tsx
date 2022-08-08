'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {ParticipantResource} from '../../ResourceTypes'

interface ListingProps {
  participantList: Array<ParticipantResource>
}

const Listing = ({participantList}: ListingProps) => {
  const adminOption = (choice: string, participantId: number) => {
    console.log(choice, participantId)
  }

  const options = (participantId: number) => {
    return (
      <select onChange={(e) => adminOption(e.target.value, participantId)}>
        <option value="default"></option>
        <option value="edit">Edit</option>
        <option value="ban">Ban</option>
        <option value="delete">Delete</option>
      </select>
    )
  }

  const rows = participantList.map((value) => {
    return (
      <tr key={`participant-${value.id}`}>
        <td>{options(value.id)}</td>
        <td>{value.email}</td>
        <td>{value.lastName.length > 0 ? value.lastName : <em>Empty</em>}</td>
        <td>{value.firstName.length > 0 ? value.firstName : <em>Empty</em>}</td>
        <td>{value.created}</td>
        <td>{value.updated}</td>
      </tr>
    )
  })

  let emptyMessage
  if (participantList.length === 0) {
    emptyMessage = <div>No participants found.</div>
  }
  return (
    <div>
      <h2>Participants</h2>
      <table className="table table-striped">
        <tbody>
          <tr>
            <th></th>
            <th>Email</th>
            <th>Last name</th>
            <th>First name</th>
            <th>Joined</th>
            <th>Updated</th>
          </tr>
          {rows}
        </tbody>
      </table>
      {emptyMessage}
    </div>
  )
}

Listing.propTypes = {participantListing: PropTypes.array}
export default Listing
