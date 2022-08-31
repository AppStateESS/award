'use strict'
import React, {useState, Dispatch, SetStateAction} from 'react'
import PropTypes from 'prop-types'
import {AwardResource} from '../../ResourceTypes'
import {deleteItem} from '../../Share/XHR'
import {activate} from '../../Share/AwardXHR'
import Modal from '../../Share/Modal'
import DeletePrompt from './DeletePrompt'
import './style.css'

interface ListingProps {
  awardList: Array<AwardResource>
  reload: () => void
  setAwardList: Dispatch<SetStateAction<AwardResource[]>>
}

const Listing = ({awardList, reload, setAwardList}: ListingProps) => {
  const [deleteModal, setDeleteModal] = useState(false)
  const [currentAward, setCurrentAward] = useState<AwardResource>()
  const [deleteConfirm, setDeleteConfirm] = useState(false)

  const select = (award: AwardResource, key: number) => {
    const [current, setCurrent] = useState('option')

    const updateActive = () => {
      const active = !award.active
      award.active = active
      activate(award.id, active).then(() => {
        awardList[key] = award
        setAwardList([...awardList])
      })
    }

    return (
      <select
        value={current}
        onChange={(e) => {
          switch (e.target.value) {
            case '1':
              location.href = `./award/User/Award/${award.id}`
              break
            case '2':
              location.href = `./award/Admin/Cycle/?awardId=${award.id}`
              break
            case '3':
              location.href = `./award/Admin/Award/${award.id}/edit`
              break
            case '4':
              updateActive()
              break
            case '5':
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
        <option value="4">{award.active ? 'Deactivate' : 'Activate'}</option>
        <option value="5">Delete</option>
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
            <th>Term</th>
            <th>Active</th>
          </tr>
          {awardList.map((value: AwardResource, key: number) => {
            return (
              <tr key={`award-${value.id}`}>
                <td>{select(value, key)}</td>
                <td>{value.title}</td>
                <td>{value.cycleTerm}</td>
                <td>
                  {value.active ? (
                    <span className="text-success">Yes</span>
                  ) : (
                    <span className="text-danger">No</span>
                  )}
                </td>
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
