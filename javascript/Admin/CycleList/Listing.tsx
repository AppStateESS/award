'use strict'
import React, {useState} from 'react'
import PropTypes from 'prop-types'
import {deleteItem} from '../../Share/XHR'
import {CycleResource} from '../../ResourceTypes'
import Modal from '../../Share/Modal'
import DeletePrompt from './DeletePrompt'

const Listing = ({
  cycleListing,
  reload,
}: {
  cycleListing: CycleResource[]
  reload: () => void
}) => {
  const [deleteModal, setDeleteModal] = useState(false)
  const [currentCycle, setCurrentCycle] = useState<CycleResource>()
  const [deleteConfirm, setDeleteConfirm] = useState(false)

  const select = (cycle: CycleResource) => {
    const [current, setCurrent] = useState('option')
    return (
      <select
        value={current}
        defaultValue="option"
        onChange={(e) => {
          switch (e.target.value) {
            case '1':
              location.href = `./award/Admin/Cycle/${cycle.id}`
              break
            case '2':
              location.href = `./award/Admin/Cycle/${cycle.id}/edit`
              break
            case '3':
              setCurrent('option')
              setCurrentCycle(cycle)
              setDeleteModal(true)
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
    )
  }

  if (cycleListing.length === 0) {
    return <p>No cycles for this award found.</p>
  }

  const rows = cycleListing.map((value) => {
    return (
      <tr key={`cycle-${value.id}`}>
        <td>{select(value)}</td>
        <td>{value.awardMonth}</td>
        <td>{value.awardYear}</td>
        <td>{value.term}</td>
        <td>{value.startDate}</td>
        <td>{value.endDate}</td>
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
        <tbody>{rows}</tbody>
      </table>
    </div>
  )
}

Listing.propTypes = {cycleListing: PropTypes.array}
export default Listing
