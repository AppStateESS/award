'use strict'
import React, {useState, useEffect} from 'react'
import PropTypes from 'prop-types'
import {confirmStatus} from '../../Share/Invitation'

type Props = {listing: Array<any>}
const NewInvites = ({listing}: Props) => {
  return (
    <table className="table table-striped">
      <tbody>
        <tr>
          <th>Email</th>
          <th>Date sent</th>
        </tr>
        {listing.map((value) => {
          return (
            <tr key={`invite-${value.id}`}>
              <td>
                <a href={`mailto:${value.email}`}>{value.email}</a>
              </td>
              <td>{value.created}</td>
            </tr>
          )
        })}
      </tbody>
    </table>
  )
}

NewInvites.propTypes = {listing: PropTypes.array}
export default NewInvites
