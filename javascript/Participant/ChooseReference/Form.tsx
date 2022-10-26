'use strict'
import React, {useState, useRef} from 'react'
import {Input} from '../../Share/Form/Form'
import AsyncSelect from 'react-select/async'
import {sendParticipantReferenceInvitation} from '../../Share/InvitationXHR'
import axios from 'axios'
import {MessageType} from '../../Share/Message'
import PropTypes from 'prop-types'

type Props = {
  cycleId: number
  nominationId: number
  inviteSent: ({message, type}: MessageType) => void
}

const Form = ({inviteSent, cycleId, nominationId}: Props) => {
  const [participantId, setParticipantId] = useState(0)
  const [email, setEmail] = useState('')

  const trackParticipant = useRef(0)

  const getOptions = (identity: string) => {
    const hold = () => {
      return new Promise<void>((resolve) => {
        clearTimeout(trackParticipant.current)
        if (identity.length > 3) {
          trackParticipant.current = window.setTimeout(() => {
            resolve()
          }, 1000)
        }
      })
    }

    return hold().then(() => {
      return axios
        .get('award/Participant/Participant/referenceAvailable', {
          headers: {'X-Requested-With': 'XMLHttpRequest'},
          params: {search: identity, nominationId, cycleId},
        })
        .then((resource) => {
          clearTimeout(trackParticipant.current)
          return resource.data
        })
    })
  }

  const setParticipant = (value: any) => {
    setParticipantId(value.value)
  }

  const inviteParticipant = () => {
    sendParticipantReferenceInvitation(participantId, cycleId).then(
      (response) => {
        if (response.data.success) {
          inviteSent({
            message: 'Reference invitation sent. Refreshing...',
            type: 'success',
          })
        } else {
          inviteSent({message: response.data.message, type: 'danger'})
        }
      }
    )
  }

  return (
    <div>
      <p>
        Search for a current participant or enter an email address to send an
        invitation to join as a reference.
      </p>
      <p>Be aware, the reference request may be refused.</p>
      <div className="row form-group">
        <div className="col-sm-6">
          <AsyncSelect
            placeholder="Search participants"
            cacheOptions
            onChange={setParticipant}
            noOptionsMessage={() => 'No matching participants found.'}
            loadOptions={getOptions}
          />
        </div>
        <div className="col-sm-4">
          <button
            disabled={participantId === 0}
            className="btn btn-primary"
            onClick={inviteParticipant}>
            Invite reference participant
          </button>
        </div>
      </div>
      <hr />
      <div className="row form-group">
        <div className="col-sm-6">
          <Input
            value={email}
            update={setEmail}
            placeholder="Enter email of non-participant"
          />
        </div>
        <div className="col-sm-4">
          <button className="btn btn-primary">Send invitation</button>
        </div>
      </div>
    </div>
  )
}

Form.propTypes = {
  inviteSent: PropTypes.func,
}

export default Form