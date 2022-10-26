'use strict'
import React, {useState, useEffect} from 'react'
import {createRoot} from 'react-dom/client'
import {ParticipantResource} from '../../ResourceTypes'
import Modal from '../../Share/Modal'
import {getList} from '../../Share/XHR'
import Current from './Current'
import Form from './Form'
import {Message, MessageType} from '../../Share/Message'

declare const cycleId: number
declare const nominationId: number
declare const referencesRequired: number
declare const canSend: boolean
declare const sendReason: string
declare const lastSent: string

/**
 *  TODO - this script is almost a duplicate of the choose judges form.
 *         The work needs to be broken up.
 *  TODO - do not show reminder if no references have been selected.
 * @returns
 */

const SendReminder = () => {
  if (canSend) {
    return (
      <div>
        <span className="badge badge-success float-right">
          Last reminder vote sent: {lastSent}
        </span>
        <a
          className="btn btn-outline-dark btn-sm "
          href={`./award/Participant/Reference/remind/?nominationId=${nominationId}`}>
          Send reminder
        </a>
      </div>
    )
  } else if (sendReason === 'too_soon') {
    return (
      <div className="badge badge-danger">
        Last votex reminder sent: {lastSent}
      </div>
    )
  } else {
    return <span></span>
  }
}

const ChooseReference = () => {
  const [referenceList, setReferenceList] = useState<ParticipantResource[]>([])
  const [inviteModal, setInviteModal] = useState(false)
  const [loading, setLoading] = useState(true)
  const [formKey, setFormKey] = useState(0)
  const [message, setMessage] = useState<MessageType>({
    message: '',
    type: '',
  })

  useEffect(() => {
    const controller = loadReferences()
    return () => controller.abort()
  }, [])

  useEffect(() => {
    setFormKey(new Date().getTime())
  }, [inviteModal])

  const loadReferences = () => {
    const controller = new AbortController()
    const {signal} = controller
    const url = './award/Participant/Reference/'
    const params = {nominationId}
    const handleSuccess = (rows: ParticipantResource[]) => {
      setReferenceList(rows)
      setLoading(false)
    }
    getList({url, params, handleSuccess, signal})
    return controller
  }

  let content
  if (loading) {
    content = <div>Loading references</div>
  } else if (referenceList.length === 0) {
    content = (
      <div className="text-secondary">
        N<em>o references assigned. Click the + button to add a reference.</em>
      </div>
    )
  } else {
    content = <Current {...{referenceList}} />
  }

  const invite = () => {
    setInviteModal(true)
  }

  const inviteSent = (inviteMessage: MessageType) => {
    setMessage(inviteMessage)
    setTimeout(() => {
      window.location.reload()
    }, 3000)
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
      <Message message={message} />
      <div className="card">
        <div className="card-header p-2">
          <button
            title="Invite reference"
            onClick={invite}
            className="btn btn-sm btn-outline-primary float-right">
            +
          </button>
          <h4 className="m-0">References - Required {referencesRequired}</h4>
        </div>
        <div className="card-body">{content}</div>
        <div className="card-footer">
          <SendReminder />
        </div>
      </div>
    </div>
  )
}

const container = document.getElementById('ChooseReference') as HTMLElement
const root = createRoot(container)
root.render(<ChooseReference />)
