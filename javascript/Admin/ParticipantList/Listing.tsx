'use strict'
import React, {useState, useEffect} from 'react'
import PropTypes from 'prop-types'
import {ParticipantResource} from '../../ResourceTypes'
import ListOptions from './ListOptions'
import BanForm from './BanForm'
import EditForm from './EditForm'
import DeleteForm from './DeleteForm'
import Modal from '../../Share/Modal'

interface ListingProps {
  participantList: Array<ParticipantResource>
  load: () => void
}

const Listing = ({participantList, load}: ListingProps) => {
  const [show, setShow] = useState(false)
  const [modalTitle, setModalTitle] = useState('')
  const [modalContentType, setModalContentType] = useState('')
  const [modalContent, setModalContent] = useState(<span></span>)
  const [currentParticipant, setCurrentParticipant] = useState<
    ParticipantResource | undefined
  >()
  const [modalKey, setModalKey] = useState(new Date().getMilliseconds())

  const loadParticipant = (key: number) => {
    setCurrentParticipant(participantList[key])
  }

  const close = () => {
    setShow(false)
  }

  useEffect(() => {
    if (modalContentType !== '') {
      switch (modalContentType) {
        case 'edit':
          setModalTitle('Update participant')
          setModalContent(<EditForm {...{currentParticipant, close, load}} />)
          break
        case 'ban':
          setModalTitle('Ban participant')
          setModalContent(<BanForm {...{currentParticipant, close}} />)
          break
        case 'delete':
          setModalTitle('Remove participant')
          setModalContent(<DeleteForm {...{currentParticipant, close}} />)
          break
      }
      setShow(true)
    }
  }, [modalContentType])

  useEffect(() => {
    if (!show) {
      setModalKey(new Date().getMilliseconds())
      setModalContentType('')
    }
  }, [show])

  const rows = participantList.map((value, key) => {
    return (
      <tr key={`participant-${value.id}`}>
        <td>
          <ListOptions
            {...{
              setModalContentType,
              participantKey: key,
              loadParticipant,
            }}
          />
        </td>
        <td>{value.email}</td>
        <td>{value.lastName || <em>Empty</em>}</td>
        <td>{value.firstName || <em>Empty</em>}</td>
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
      <Modal
        key={modalKey}
        {...{show, title: modalTitle, close, includeCloseButton: false}}>
        {modalContent}
      </Modal>
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
