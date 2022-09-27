'use strict'
import React, {useState, useEffect, useRef} from 'react'
import {createRoot} from 'react-dom/client'
import {getList} from '../../Share/XHR'
import Loading from '../../Share/Loading'
import Listing from './Listing'
import Modal from '../../Share/Modal'
import {ParticipantResource} from '../../ResourceTypes'
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
  const [search, setSearch] = useState('')
  const [error, setError] = useState<string>()
  const [participantList, setParticipantList] = useState<ParticipantResource[]>(
    []
  )
  const searchTimer = useRef()
  const [formKey, setFormKey] = useState(new Date().getTime())
  const [inviteModal, setInviteModal] = useState(false)

  useEffect(() => {
    load()
  }, [])

  useEffect(() => {
    clearTimeout(searchTimer.current)
    if (search.length > 3) {
      searchTimer.current = setTimeout(() => {
        load()
        clearTimeout(searchTimer.current)
      }, 1000)
    }
  }, [search])

  useEffect(() => {
    setFormKey(new Date().getTime())
  }, [inviteModal])

  const load = () => {
    setLoading(true)
    const controller = new AbortController()
    const data = {search: search.length > 3 ? search : ''}
    const params = {
      url: './award/Admin/Participant/',
      handleSuccess: (data: ParticipantResource[]) => {
        setLoading(false)
        setParticipantList(data)
      },
      params: data,
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
        <div className="row">
          <div className="col-sm-6 d-flex align-items-center">
            <button
              className="btn btn-success"
              onClick={() => setInviteModal(true)}>
              Invite participant
            </button>
          </div>
          <div className="col-sm-6">
            <div className="form-group">
              <label htmlFor="search"></label>
              <input
                type="text"
                className="form-control"
                placeholder="Search..."
                name="search"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
              />
            </div>
          </div>
        </div>
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
        <Listing participantList={participantList} load={load} />
      </div>
    )
  }
}

ParticipantList.propTypes = {}

const container = document.getElementById('ParticipantList') as HTMLElement
const root = createRoot(container)
root.render(<ParticipantList />)
