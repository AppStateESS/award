'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {ParticipantResource} from '../../ResourceTypes'

interface ListingProps {
  participantList: Array<ParticipantResource>
}

const Listing = ({participantList}: ListingProps) => {
  let rows
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
            <th>Header</th>
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
