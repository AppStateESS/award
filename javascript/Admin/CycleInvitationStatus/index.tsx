'use strict'
import React, {useState, useEffect} from 'react'
import {getCycleInvitations} from '../../Share/InvitationXHR'
import {InvitationResource} from '../../ResourceTypes'
import Loading from '../../Share/Loading'
import {createRoot} from 'react-dom/client'

declare const cycleId: number

const CycleInvitationStatus = () => {
  const [invitationList, setInvitationList] = useState<
    InvitationResource[] | null
  >()
  const [loading, setLoading] = useState(true)
  const [serverError, setServerError] = useState(false)

  useEffect(() => {
    load()
  }, [])

  const confirmStatus = (confirm: number) => {
    switch (confirm) {
      case 0:
        return <span className="badge badge-info text-white">No response</span>
      case 1:
        return <span className="badge badge-success">Confirmed</span>
      case 2:
        return <span className="badge badge-danger">Refused</span>
    }
  }

  const load = () => {
    setLoading(true)
    getCycleInvitations(cycleId)
      .then((response) => {
        setLoading(false)
        setInvitationList(response.data)
      })
      .catch((_error) => {
        setLoading(false)
        setServerError(true)
      })
  }

  let content = <Loading things="invitations" />
  if (serverError) {
    content = <div className="alert alert-danger">Server error</div>
  } else if (!loading) {
    const rows = invitationList?.map((value) => {
      return (
        <tr key={`invite-${value.id}`}>
          <td>
            {value.firstName} {value.lastName}
          </td>
          <td>{value.email}</td>
          <td>{}</td>
          <td>{confirmStatus(value.confirm)}</td>
        </tr>
      )
    })
    content = (
      <table className="table">
        <tbody>{rows}</tbody>
      </table>
    )
  }

  return (
    <div>
      <div className="card">
        <div className="card-header p-2">
          <h4 className="m-0">Invitation status</h4>
        </div>
        <div className="card-body">{content}</div>
      </div>
    </div>
  )
}

const container = document.getElementById(
  'CycleInvitationStatus'
) as HTMLElement
const root = createRoot(container)
root.render(<CycleInvitationStatus />)
