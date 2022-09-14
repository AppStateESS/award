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
declare const canSend: boolean
declare const sendReason: string
declare const lastSent: string

const SendReminder = () => {
  if (canSend) {
    return (
      <div>
        <span className="badge badge-success float-right">
          Last reminder vote sent {lastSent}
        </span>
        <a
          className="btn btn-outline-dark btn-sm "
          href={`./award/Admin/Judge/remind/?cycleId=${cycleId}`}>
          Send reminder
        </a>
      </div>
    )
  } else if (sendReason === 'early') {
    return <span></span>
  } else if (sendReason === 'too_soon') {
    return (
      <div className="badge badge-danger">
        Last vote reminder sent {lastSent}
      </div>
    )
  } else {
    return <span></span>
  }
}

console.log(cycleId, canSend, sendReason, lastSent)

const Judges = () => {
  const [judgeList, setJudgeList] = useState<ParticipantResource[]>([])
  const [inviteModal, setInviteModal] = useState(false)
  const [loading, setLoading] = useState(true)
  const [formKey, setFormKey] = useState(0)
  const [message, setMessage] = useState<MessageType>({
    message: '',
    type: '',
  })

  useEffect(() => {
    const controller = loadJudges()
    return () => controller.abort()
  }, [])

  useEffect(() => {
    setFormKey(new Date().getTime())
  }, [inviteModal])

  const loadJudges = () => {
    const controller = new AbortController()
    const {signal} = controller
    const url = './award/Admin/Judge/'
    const params = {cycleId}
    const handleSuccess = (rows: ParticipantResource[]) => {
      setJudgeList(rows)
      setLoading(false)
    }
    getList({url, params, handleSuccess, signal})
    return controller
  }

  let content
  if (loading) {
    content = <div>Loading judges</div>
  } else if (judgeList.length === 0) {
    content = <div>No judges assigned.</div>
  } else {
    content = <Current {...{judgeList}} />
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
        title="Invite judge"
        size="lg"
        show={inviteModal}
        close={() => setInviteModal(false)}>
        <Form key={formKey} inviteSent={inviteSent} cycleId={cycleId} />
      </Modal>
      <Message message={message} />
      <div className="card">
        <div className="card-header p-2">
          <button
            title="Invite judge"
            onClick={invite}
            className="btn btn-sm btn-outline-primary float-right">
            +
          </button>
          <h4 className="m-0">Judges</h4>
        </div>
        <div className="card-body">{content}</div>
        <div className="card-footer">
          <SendReminder />
        </div>
      </div>
    </div>
  )
}

const container = document.getElementById('Judges') as HTMLElement
const root = createRoot(container)
root.render(<Judges />)
