'use strict'
import React, {useState} from 'react'
import PropTypes from 'prop-types'
import {deleteItem} from '../../Share/XHR'
import {CycleResource, AwardBasic} from '../../ResourceTypes'
import Modal from '../../Share/Modal'
import DeletePrompt from './DeletePrompt'

const Listing = ({
  cycleListing,
  reload,
  award,
}: {
  cycleListing: CycleResource[]
  reload: () => void
  award: AwardBasic
}) => {
  const [deleteModal, setDeleteModal] = useState(false)
  const [currentCycle, setCurrentCycle] = useState<CycleResource>()
  const [deleteConfirm, setDeleteConfirm] = useState(false)

  const select = (cycle: CycleResource) => {
    const [current, setCurrent] = useState('option')

    const deleteCycleModal = () => {
      setCurrentCycle(cycle)
      setDeleteModal(true)
    }
    const optionChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
      switch (event.target.value) {
        case '1':
          location.href = `./award/Admin/Cycle/${cycle.id}`
          break
        case '2':
          location.href = `./award/Admin/Cycle/${cycle.id}/edit`
          break
        case '3':
          deleteCycleModal()
          setCurrent('option')
          break
      }
    }
    return (
      <select value={current} onChange={optionChange}>
        <option disabled value="option">
          -- Options --
        </option>
        <option value="1">View</option>
        <option value="2">Edit</option>
        <option value="3">Delete</option>
      </select>
    )
  }

  if (cycleListing.length === 0) {
    return (
      <div className="lead text-center mt-5">
        No cycles for this award were found.
      </div>
    )
  }

  const rows = cycleListing.map((value) => {
    const judge = value.judgeMethod === 1 ? 'Judged' : 'Popular vote'
    const startDate = new Date(value.endDate * 1000)
    const endDate = new Date(value.endDate * 1000)
    return (
      <tr key={`cycle-${value.id}`}>
        <td>{select(value)}</td>
        {award.cycleTerm === 'monthly' && <td>{value.awardMonth}</td>}
        <td>{value.awardYear}</td>
        <td>{judge}</td>
        <td>{value.voteType}</td>
        <td>
          {startDate.toLocaleTimeString()}, {startDate.toLocaleDateString()}
        </td>
        <td>
          {endDate.toLocaleTimeString()}, {endDate.toLocaleDateString()}
        </td>
      </tr>
    )
  })

  const deleteCycle = () => {
    setDeleteConfirm(false)
    setDeleteModal(false)
    if (currentCycle) {
      deleteItem('Admin', 'Cycle', currentCycle.id).then(() => {
        reload()
      })
    }
  }

  let deleteButton
  if (deleteConfirm) {
    deleteButton = (
      <button className="btn btn-danger" onClick={deleteCycle}>
        Are you sure?
      </button>
    )
  } else {
    deleteButton = (
      <button
        className="btn btn-danger"
        onClick={() => {
          setDeleteConfirm(true)
        }}>
        Delete
      </button>
    )
  }
  return (
    <div>
      <Modal
        title="Delete cycle"
        show={deleteModal}
        button={deleteButton}
        close={() => {
          setDeleteModal(false)
          setDeleteConfirm(false)
        }}>
        <DeletePrompt cycle={currentCycle} />
      </Modal>
      <table className="table table-striped">
        <tbody>
          <tr>
            <th></th>
            {award.cycleTerm === 'monthly' && <th>Month</th>}
            <th>Year</th>
            <th>Judge type</th>
            <th>Vote type</th>
            <th>Begins</th>
            <th>Deadline</th>
          </tr>
          {rows}
        </tbody>
      </table>
    </div>
  )
}

Listing.propTypes = {cycleListing: PropTypes.array}
export default Listing
