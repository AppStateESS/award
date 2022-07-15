'use strict'
import React, {useState, useEffect} from 'react'
import {getList} from '../../Share/XHR'
import {createRoot} from 'react-dom/client'
import Loading from '../../Share/Loading'
import NewInvites from './NewInvites'
import Select from 'react-select'
import {getSelectList} from '../../Share/AwardXHR'
import ParticipantInvites from './ParticipantInvites'

type CurrentAward = {
  value: number
  label: string
}

const InvitationList = () => {
  const [loading, setLoading] = useState(false)
  const [listing, setListing] = useState<Array<any | null>>([])
  const [currentAward, setCurrentAward] = useState<CurrentAward | null>()
  const [awardList, setAwardList] = useState([])
  const [inviteType, setInviteType] = useState(0)
  const [confirm, setConfirm] = useState(0)
  const [initComplete, setInitComplete] = useState(false)

  useEffect(() => {
    getSelectList().then((response) => {
      setInitComplete(true)
      setAwardList(response.data)
    })
    load()
  }, [])

  useEffect(() => {
    if (initComplete) {
      load()
    }
  }, [inviteType, confirm, currentAward])

  const load = () => {
    setLoading(true)
    const url = 'award/Admin/Invitation/'
    const awardId = currentAward && currentAward.value ? currentAward.value : 0

    const params = {awardId, inviteType, confirm}
    const handleSuccess = (data: Array<any>) => {
      setListing(typeof data === 'object' ? data : [])
      setLoading(false)
    }
    const controller = new AbortController()
    const {signal} = controller
    getList({url, handleSuccess, signal, params})
  }

  const isActive = (compareConfirm: number) => {
    return compareConfirm !== confirm ? 'btn-outline-' : 'active btn-'
  }

  let content

  if (loading) {
    content = <Loading things="invitations" />
  }

  if (listing.length === 0) {
    content = (
      <div>
        <p>No invitations found.</p>
      </div>
    )
  } else if (confirm === 0) {
    content = <NewInvites {...{listing}} />
  } else {
    content = <ParticipantInvites {...{listing}} />
  }
  return (
    <div>
      <h2>Invitations</h2>
      <hr />
      <ul className="nav nav-tabs mb-3">
        <li className="nav-item">
          <a
            className={`btn btn-link nav-link ${inviteType === 0 && 'active'}`}
            aria-current="page"
            onClick={() => setInviteType(0)}>
            New Participants
          </a>
        </li>
        <li className="nav-item">
          <a
            className={`btn btn-link nav-link ${inviteType === 1 && 'active'}`}
            aria-current="page"
            onClick={() => setInviteType(1)}>
            Judges
          </a>
        </li>
        <li className="nav-item">
          <a
            className={`btn btn-link nav-link ${inviteType === 2 && 'active'}`}
            aria-current="page"
            onClick={() => setInviteType(2)}>
            References
          </a>
        </li>
        <li className="nav-item">
          <a
            className={`btn btn-link nav-link ${inviteType === 3 && 'active'}`}
            aria-current="page"
            onClick={() => setInviteType(3)}>
            Nominees
          </a>
        </li>
      </ul>
      <div className="row my-3">
        <div className="col-sm-6">
          <div
            className="btn-group"
            role="group"
            aria-label="Confirmation status">
            <button
              type="button"
              onClick={() => setConfirm(0)}
              className={`btn ${isActive(0)}primary`}>
              Waiting
            </button>
            <button
              type="button"
              onClick={() => setConfirm(1)}
              className={`btn ${isActive(1)}success`}>
              Confirmed
            </button>
            <button
              type="button"
              onClick={() => setConfirm(2)}
              className={`btn ${isActive(2)}danger`}>
              Refused
            </button>
          </div>
        </div>
        {confirm !== 0 && (
          <div className="col-sm-6">
            <Select
              placeholder="Filter by award"
              options={awardList}
              onChange={(newValue) => setCurrentAward(newValue)}
            />
          </div>
        )}
      </div>
      {content}
    </div>
  )
}

const container = document.getElementById('InvitationList') as HTMLElement
const root = createRoot(container)
root.render(<InvitationList />)
