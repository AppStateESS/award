'use strict'
import React, {Fragment} from 'react'
import PropTypes from 'prop-types'

const Listing = ({cycleListing}) => {
  const select = (id) => {
    return (
      <Fragment>
        <select
          defaultValue="option"
          onChange={(e) => {
            switch (e.target.value) {
              case '1':
                location.href = `./award/Admin/Cycle/${id}`
                break
              case '2':
                location.href = `./award/Admin/Cycle/${id}/edit`
                break
              case '3':
                if (confirm('Are you sure you want to delete this cycle?')) {
                  console.log('DELETED!')
                }
                break
            }
          }}>
          <option disabled value="option">
            -- Options --
          </option>
          <option value="1">View</option>
          <option value="2">Edit</option>
          <option value="3">Delete</option>
        </select>
      </Fragment>
    )
  }

  if (cycleListing.length === 0) {
    return <p>No cycles for this award found.</p>
  }

  const rows = cycleListing.map((value) => {
    return (
      <tr key={`cycle-${value.id}`}>
        <td>{select(value.id)}</td>
        <td>{value.awardMonth}</td>
        <td>{value.awardYear}</td>
        <td>{value.term}</td>
        <td>{value.startDate}</td>
        <td>{value.endDate}</td>
      </tr>
    )
  })
  return (
    <div>
      <table className="table table-striped">
        <tbody>{rows}</tbody>
      </table>
    </div>
  )
}

Listing.propTypes = {cycleListing: PropTypes.array}
export default Listing
