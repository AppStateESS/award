'use strict'
import React from 'react'
import {ParticipantResource} from '../../ResourceTypes'
import axios from 'axios'
import PropTypes from 'prop-types'

interface SubTrustParams {
  participantId: number
  close: () => void
  load: VoidFunction
}

const toggleTrust = (
  participantId: number,
  trust: boolean,
  close: VoidFunction,
  load: VoidFunction
) => {
  const url = `award/Admin/Participant/${participantId}/trust`
  const data = {trust}

  axios({
    method: 'patch',
    url,
    data,
    timeout: 3000,
    headers: {'X-Requested-With': 'XMLHttpRequest'},
  }).then((response) => {
    load()
    close()
  })
}

const Trust = ({participantId, close, load}: SubTrustParams) => {
  return (
    <div>
      <p>
        Trusting this participant will allow them to nominate others for awards.
      </p>
      <p>This will also allow them to see other participants on the system.</p>
      <button
        className="btn btn-success btn-block"
        onClick={() => toggleTrust(participantId, true, close, load)}>
        Trust participant
      </button>
      <button className="btn btn-danger btn-block" onClick={close}>
        Cancel
      </button>
    </div>
  )
}

const Untrust = ({participantId, close, load}: SubTrustParams) => {
  return (
    <div>
      <p>
        Untrusting this participant will prevent them from nominating others for
        awards.
      </p>
      <button
        className="btn btn-success btn-block"
        onClick={() => toggleTrust(participantId, false, close, load)}>
        Untrust participant
      </button>
      <button className="btn btn-danger btn-block" onClick={close}>
        Cancel
      </button>
    </div>
  )
}

type TrustFormProps = {
  currentParticipant: ParticipantResource
  close: () => void
  load: VoidFunction
}

const TrustForm = ({currentParticipant, close, load}: TrustFormProps) => {
  let content
  if (currentParticipant.trusted === 1) {
    return (
      <Untrust
        participantId={currentParticipant.id}
        close={close}
        load={load}
      />
    )
  } else {
    return (
      <Trust participantId={currentParticipant.id} close={close} load={load} />
    )
  }
}

TrustForm.propTypes = {
  currentParticipant: PropTypes.object,
  close: PropTypes.func,
  load: PropTypes.func,
}
export default TrustForm
