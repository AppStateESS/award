'use strict'
import React, {useState, useEffect} from 'react'
import {createRoot} from 'react-dom/client'
import {getList} from '../../Share/XHR'
import {invitationDefault} from '../../Share/DefaultResource'
import Loading from '../../Share/Loading'
import Listing from './Listing'
import Modal from '../../Share/Modal'
import {ParticipantResource, InvitationResource} from '../../ResourceTypes'
import InviteForm from './InviteForm'
import {AxiosError} from 'axios'

const ErrorAlert = ({
  message,
  close,
}: {
  message: string
  close?: () => void
}) => {
  let closeButton
  if (close) {
    closeButton = (
      <button className="btn btn-link" onClick={() => close()}>
        X
      </button>
    )
  }
  return (
    <div className="alert alert-danger">
      Error: {message} {closeButton}
    </div>
  )
}

const ParticipantList = () => {
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string>()
  const [participantList, setParticipantList] = useState<ParticipantResource[]>(
    []
  )
  const [formKey, setFormKey] = useState(new Date().getTime())
  const [inviteModal, setInviteModal] = useState(false)

  useEffect(() => {
    load()
  }, [])

  useEffect(() => {
    setFormKey(new Date().getTime())
  }, [inviteModal])

  const load = () => {
    setLoading(true)
    const controller = new AbortController()
    const params = {
      url: './award/Admin/Participant/',
      handleSuccess: (data: ParticipantResource[]) => {
        setLoading(false)
        setParticipantList(data)
      },
      handleError: (axiosError: AxiosError) => {
        setError(axiosError.response?.statusText)
        setLoading(false)
      },
      signal: controller.signal,
    }

    getList(params)
  }

  if (loading) {
    return <Loading things="participants" />
  } else {
    return (
      <div>
        {error && <ErrorAlert message={error} close={() => setError('')} />}
        <button
          className="btn btn-outline-dark"
          onClick={() => setInviteModal(true)}>
          Invite participant
        </button>
        <hr />
        <Modal
          title="Invite participant"
          show={inviteModal}
          includeCloseButton={false}
          close={() => {
            setInviteModal(false)
          }}>
          <InviteForm
            key={formKey}
            close={() => {
              setInviteModal(false)
            }}
          />
        </Modal>
        <Listing participantList={participantList} />
      </div>
    )
  }
}

ParticipantList.propTypes = {}

const container = document.getElementById('ParticipantList') as HTMLElement
const root = createRoot(container)
root.render(<ParticipantList />)
