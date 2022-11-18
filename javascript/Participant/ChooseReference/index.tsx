'use strict'
import React, {useState, useEffect} from 'react'
import {createRoot} from 'react-dom/client'
import {ReferenceInvitation, ReferenceResource} from '../../ResourceTypes'
import Modal from '../../Share/Modal'
import {getList} from '../../Share/XHR'
import Current from './Current'
import Form from './Form'
import Invitation from './Invitation'
import Message from '../../Share/Message'

declare const cycleId: number
declare const nominationId: number
declare const referencesRequired: number
declare const referenceReasonRequired: boolean
declare const reminderGrace: number

/**
 *  TODO - this script is almost a duplicate of the choose judges form.
 *         The work needs to be broken up.
 *  TODO - do not show reminder if no references have been selected.
 */

const ChooseReference = () => {
  const [referenceList, setReferenceList] = useState<ReferenceResource[]>([])
  const [invitationList, setInvitationList] = useState<ReferenceInvitation[]>(
    []
  )
  const [inviteModal, setInviteModal] = useState(false)
  const [referencesLoading, setReferencesLoading] = useState(true)
  const [invitationLoading, setInvitationLoading] = useState(true)
  const [formKey, setFormKey] = useState(0)
  const [message, setMessage] = useState('')
  const [messageType, setMessageType] = useState('danger')

  useEffect(() => {
    const controller1 = loadReferences()
    const controller2 = loadInvitations()
    return () => {
      controller1.abort()
      controller2.abort()
    }
  }, [])

  useEffect(() => {
    setFormKey(new Date().getTime())
  }, [inviteModal])

  const loadReferences = () => {
    const controller = new AbortController()
    const {signal} = controller
    const url = './award/Participant/Reference/'
    const params = {nominationId}
    const handleSuccess = (rows: ReferenceResource[]) => {
      setReferenceList(rows)
      setReferencesLoading(false)
    }
    getList({url, params, handleSuccess, signal})
    return controller
  }

  const loadInvitations = () => {
    const controller = new AbortController()
    const {signal} = controller
    const url = './award/Participant/Invitation/reference'
    const params = {nominationId}
    const handleSuccess = (rows: ReferenceInvitation[]) => {
      setInvitationList(rows)
      setInvitationLoading(false)
    }
    getList({url, params, handleSuccess, signal})
    return controller
  }

  let currentInvites
  let currentReferences

  if (referencesLoading) {
    currentReferences = <div>Loading references</div>
  } else if (referenceList.length === 0) {
    currentReferences = (
      <div>
        <em>
          No references assigned. Please invite a participant below. Your
          nomination must have at least {referencesRequired} reference
          {referencesRequired > 1 ? 's' : ''}.
        </em>
      </div>
    )
  } else {
    currentReferences = (
      <Current {...{referenceList, referenceReasonRequired, reminderGrace}} />
    )
  }

  if (invitationLoading) {
    currentInvites = <div>Loading invitations</div>
  } else if (invitationList.length === 0) {
    currentInvites = (
      <div className="text-secondary">
        <em>No invitations sent.</em>
      </div>
    )
  } else {
    currentInvites = <Invitation {...{invitationList}} />
  }

  const invite = () => {
    setInviteModal(true)
  }

  const inviteSent = (message: string, messageType: string, refresh = true) => {
    setMessage(message)
    setMessageType(messageType)
    if (refresh) {
      setTimeout(() => {
        window.location.reload()
      }, 3000)
    }
    setInviteModal(false)
  }

  return (
    <div>
      <Modal
        title="Invite reference"
        size="lg"
        show={inviteModal}
        close={() => setInviteModal(false)}>
        <Form
          key={formKey}
          inviteSent={inviteSent}
          cycleId={cycleId}
          nominationId={nominationId}
        />
      </Modal>
      <Message message={message} type={messageType} />
      <div className="card mb-5">
        <div className="card-header">
          <h4 className="m-0">References</h4>
        </div>
        <div className="card-body">{currentReferences}</div>
      </div>

      <div className="card">
        <div className="card-header">
          <button
            title="Invite reference"
            onClick={invite}
            className="btn btn-sm btn-primary float-right">
            + Invite
          </button>
          <h4 className="m-0">Invited</h4>
        </div>
        <div className="card-body">{currentInvites}</div>
      </div>
    </div>
  )
}

const container = document.getElementById('ChooseReference') as HTMLElement
const root = createRoot(container)
root.render(<ChooseReference />)
