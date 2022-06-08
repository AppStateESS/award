'use strict'
import React, {useState} from 'react'
import PropTypes from 'prop-types'
import {AwardResource} from '../../ResourceTypes'
import {deleteItem} from '../../Share/XHR'
import Modal from '../../Share/Modal'
import DeletePrompt from './DeletePrompt'
import './style.css'

const currentCycle = (id: number, currentCycleId: number) => {
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

interface ListingProps {
  awardList: Array<AwardResource>
  reload: () => void
}

const Listing = ({awardList, reload}: ListingProps) => {
  const [deleteModal, setDeleteModal] = useState(false)
  const [currentAward, setCurrentAward] = useState<AwardResource>()
  const [deleteConfirm, setDeleteConfirm] = useState(false)

  const select = (award: AwardResource) => {
    const [current, setCurrent] = useState('option')
    return (
      <select
        value={current}
        onChange={(e) => {
          switch (e.target.value) {
            case '1':
              location.href = `./award/Admin/Award/${award.id}`
              break
            case '2':
              location.href = `./award/Admin/Cycle/?awardId=${award.id}`
              break
            case '3':
              location.href = `./award/Admin/Award/${award.id}/edit`
              break
            case '4':
              setCurrent('option')
              setCurrentAward(award)
              setDeleteModal(true)
              break
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
    )
  }

  const deleteAward = () => {
    setDeleteConfirm(false)
    setDeleteModal(false)
    if (currentAward) {
      deleteItem('Admin', 'Award', currentAward.id).then(() => {
        reload()
      })
    }
  }

  let deleteButton
  if (deleteConfirm) {
    deleteButton = (
      <button className="btn btn-danger" onClick={deleteAward}>
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
        title={`Delete award: ${currentAward && currentAward.title}`}
        show={deleteModal}
        button={deleteButton}
        close={() => {
          setDeleteModal(false)
          setDeleteConfirm(false)
        }}>
        <DeletePrompt award={currentAward} />
      </Modal>

      <table className="table table-striped award-list table-sm">
        <tbody>
          <tr>
            <th style={{width: '15%'}}></th>
            <th>Title</th>
            <th>Current cycle</th>
          </tr>
          {awardList.map((value: AwardResource) => {
            return (
              <tr key={`award-${value.id}`}>
                <td>{select(value)}</td>
                <td>{value.title}</td>
                <td>{currentCycle(value.id, value.currentCycleId)}</td>
              </tr>
            )
          })}
        </tbody>
      </table>
    </div>
  )
}

Listing.propTypes = {awardList: PropTypes.array}
export default Listing
