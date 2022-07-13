'use strict'
import React, {useState, useEffect, useRef} from 'react'
import {Input} from '../../Share/Form/Form'
import {canInviteGeneral, sendInvitation} from '../../Share/ParticipantXHR'
import PropTypes from 'prop-types'

const InviteForm = ({close}: {close: () => void}) => {
  const [email, setEmail] = useState('')
  const [emailExists, setEmailExists] = useState(false)
  const [refused, setRefused] = useState(false)
  const [checkMade, setCheckMade] = useState(false)
  const [buttonStatus, setButtonStatus] = useState('send')

  const emailCheckTimer = useRef<ReturnType<typeof setTimeout>>()

  useEffect(() => {
    if (email.match(/.@./) !== null) {
      clearTimeout(emailCheckTimer.current)
      emailCheckTimer.current = setTimeout(() => {
        canInviteGeneral(email).then((response) => {
          setEmailExists(response.data.exists)
          setRefused(response.data.refused)
          setCheckMade(true)
        })
        clearTimeout(emailCheckTimer.current)
      }, 1000)
    }
  }, [email])

  const send = () => {
    sendInvitation(email, 0).then((response) => {
      const {result} = response.data
      setButtonStatus(result)
    })
  }

  const disabled =
    email.length === 0 ||
    email.match(/.@./) === null ||
    emailExists ||
    !checkMade ||
    refused

  let button
  switch (buttonStatus) {
    case 'send':
      button = (
        <button
          className="btn btn-primary btn-block"
          disabled={disabled}
          onClick={() => send()}>
          Invite
        </button>
      )
      break
    case 'sent':
      button = (
        <button className="btn btn-success btn-block" onClick={close}>
          Invite sent!
        </button>
      )
      break
    case 'notsent':
      button = (
        <button className="btn btn-danger btn-block" onClick={close}>
          Invite previously sent. Did not resend.
        </button>
      )
      break
  }

  return (
    <div>
      <p>
        Clicking &quot;Invite&quot; sends a Participant account invitation. If
        the recipient refuses, no further general invitations may be sent.
        Judge, reference, and nomination invites are still permitted.
      </p>
      <hr />
      <div className="row form-group">
        <div className="col-sm-4">
          <label>Email address</label>
        </div>
        <div className="col-sm-8">
          <Input
            value={email}
            update={(value) => {
              setEmail(value)
            }}
            allowEmpty={false}
          />
          {emailExists && (
            <div className="badge badge-danger">
              Participant already in system.
            </div>
          )}
          {refused && (
            <div className="badge badge-danger">
              Sorry, participant refused previous invitation.
            </div>
          )}
          {!disabled && (
            <div className="badge badge-success">Invite email acceptable.</div>
          )}
        </div>
      </div>
      {button}
    </div>
  )
}
InviteForm.propTypes = {
  close: PropTypes.func,
}

export default InviteForm
