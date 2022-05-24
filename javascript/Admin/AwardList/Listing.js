'use strict'
import React, {Fragment} from 'react'
import PropTypes from 'prop-types'
import './style.css'

const currentCycle = (id, currentCycleId) => {
  if (currentCycleId === 0) {
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
        <a href={`./award/Admin/Cycle/${currentCycleId}`}>Current</a>
      </div>
    )
  }
}

const Listing = ({awardList}) => {
  const select = (id) => {
    return (
      <Fragment>
        <select
          defaultValue="option"
          onChange={(e) => {
            switch (e.target.value) {
              case '1':
                location.href = `./award/Admin/Award/${id}`
                break
              case '2':
                location.href = `./award/Admin/Cycle/?awardId=${id}`
                break
              case '3':
                location.href = `./award/Admin/Award/${id}/edit`
                break
              case '4':
            }
          }}>
          <option disabled value="option">
            -- Options --
          </option>
          <option value="1">View award</option>
          <option value="2">Cycle list</option>
          <option value="3">Edit</option>
          <option value="4">Delete</option>
        </select>
      </Fragment>
    )
  }

  return (
    <table className="table table-striped award-list table-sm">
      <tbody>
        <tr>
          <th style={{width: '15%'}}></th>
          <th>Title</th>
          <th>Current cycle</th>
        </tr>
        {awardList.map((value) => {
          console.log(value)
          return (
            <tr key={`award-${value.id}`}>
              <td>{select(value.id)}</td>
              <td>{value.title}</td>
              <td>{currentCycle(value.id, value.currentCycleId)}</td>
            </tr>
          )
        })}
      </tbody>
    </table>
  )
}

Listing.propTypes = {awardList: PropTypes.array}
export default Listing
