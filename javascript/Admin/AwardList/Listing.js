'use strict'
import React, {useState, useEffect} from 'react'
import PropTypes from 'prop-types'

const currentCycle = ({id, cycleId, awardYear, awardMonth}) => {
  if (cycleId === null) {
    return (
      <a
        href={`./award/Admin/Cycle/create?awardId=${id}`}
        className="btn btn-sm btn-outline-primary">
        Create new
      </a>
    )
  } else {
    return (
      <div>
        {awardMonth}, {awardYear}
      </div>
    )
  }
}

const Listing = ({awardList}) => {
  const select = (id) => {
    return (
      <div className="form-group">
        <select
          onChange={(e) => {
            switch (e.target.value) {
              case 'View':
              case 'Edit':
                location.href = `./award/Admin/Award/${id}/edit`
                break
              case 'Delete':
            }
          }}>
          <option></option>
          <option>View</option>
          <option>Edit</option>
          <option>Delete</option>
        </select>
      </div>
    )
  }

  return (
    <table className="table table-striped">
      <tbody>
        <tr>
          <th style={{width: '10%'}}></th>
          <th>Title</th>
          <th>Current cycle</th>
        </tr>
        {awardList.map((value) => {
          return (
            <tr key={`award-${value.id}`}>
              <td>{select(value.id)}</td>
              <td>{value.title}</td>
              <td>{currentCycle(value)}</td>
            </tr>
          )
        })}
      </tbody>
    </table>
  )
}

Listing.propTypes = {awardList: PropTypes.array}
export default Listing
