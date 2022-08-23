'use strict'
import React, {useState, useEffect} from 'react'
import {createRoot} from 'react-dom/client'
import {getList} from '../../Share/XHR'
import {Message as WarningMessage, emptyMessage} from '../../Share/Message'
import {InvitationResource} from '../../ResourceTypes'
import Loading from '../../Share/Loading'
import {getInviteType} from '../../Share/Invitation'
import Modal from '../../Share/Modal'
import Accept from './Accept'
import Refuse from './Refuse'
import {acceptInvitation, refuseInvitation} from '../../Share/InvitationXHR'

declare const templateValue: string

const ParticipantInvitations = () => {
  const [loading, setLoading] = useState(true)
  const [invitationList, setInvitationList] = useState<InvitationResource[]>([])
  const [message, setMessage] = useState(emptyMessage)
  const [acceptModal, setAcceptModal] = useState(false)
  const [refuseModal, setRefuseModal] = useState(false)
  const [currentInvite, setCurrentInvite] = useState<
    InvitationResource | undefined
  >()

  useEffect(() => {
    loadInvitations()
  }, [])

  const acceptInviteModal = (invitationKey: number) => {
    setCurrentInvite(invitationList[invitationKey])
    setAcceptModal(true)
  }

  const finalAccept = () => {
    if (currentInvite !== undefined) {
      acceptInvitation(currentInvite.id)
        .then(() => {
          setAcceptModal(false)
          location.reload()
        })
        .catch(() => {
          setMessage({message: 'Server error', type: 'danger'})
        })
    }
  }

  const finalRefusal = () => {
    if (currentInvite !== undefined) {
      refuseInvitation(currentInvite.id)
        .then(() => {
          setAcceptModal(false)
          location.reload()
        })
        .catch(() => {
          setMessage({message: 'Server error', type: 'danger'})
        })
    }
  }

  const refuseInviteModal = (invitationKey: number) => {
    setCurrentInvite(invitationList[invitationKey])
    setRefuseModal(true)
  }

  const loadInvitations = () => {
    setLoading(true)
    const controller = new AbortController()
    const {signal} = controller
    const url = 'award/Participant/Invitation'
    const handleSuccess = (data: InvitationResource[]) => {
      setInvitationList(data)
      setLoading(false)
    }
    const handleError = () => {
      setMessage({message: 'Could not access server', type: 'danger'})
    }
    getList({url, handleSuccess, handleError, signal})
  }

  const InvitationList = () => {
    if (invitationList && invitationList.length > 0) {
      return (
        <table className="table">
          <tbody>
            <tr>
              <th>Award</th>
              <th>Invitation type</th>
              <th>Response</th>
            </tr>
            {invitationList.map((value, key) => {
              return (
                <tr key={`invite-${value.id}`}>
                  <td>{value.awardTitle}</td>
                  <td>{getInviteType(value.inviteType)}</td>
                  <td>
                    <button
                      className="px-1 py-0 mr-1 btn btn-sm btn-success"
                      onClick={() => acceptInviteModal(key)}>
                      Accept
                    </button>
                    <button
                      className="px-1 py-0 btn btn-sm btn-danger"
                      onClick={() => refuseInviteModal(key)}>
                      Refuse
                    </button>
                  </td>
                </tr>
              )
            })}
          </tbody>
        </table>
      )
    } else {
      return <span>No invitations pending</span>
    }
  }

  let content = <Loading things="invitations" />
  if (!loading) {
    content = <InvitationList />
  }

  return (
    <div>
      <WarningMessage message={message} />
      {currentInvite && (
        <Modal
          {...{
            show: acceptModal,
            title: `Accept ${getInviteType(
              currentInvite.inviteType,
              false
            )} invitation`,
            close: () => setAcceptModal(false),
            includeCloseButton: false,
          }}>
          <Accept
            {...{
              currentInvite,
              finalAccept,
              close: () => setAcceptModal(false),
            }}
          />
        </Modal>
      )}
      {currentInvite && (
        <Modal
          {...{
            show: refuseModal,
            title: `Refuse ${getInviteType(
              currentInvite.inviteType,
              false
            )} invitation`,
            close: () => setRefuseModal(false),
            includeCloseButton: false,
          }}>
          <Refuse
            {...{
              currentInvite,
              finalRefusal,
              close: () => setRefuseModal(false),
            }}
          />
        </Modal>
      )}
      {content}
    </div>
  )
}

const container = document.getElementById(
  'ParticipantInvitations'
) as HTMLElement
const root = createRoot(container)
root.render(<ParticipantInvitations />)
