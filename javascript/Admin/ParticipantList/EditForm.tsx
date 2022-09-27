'use strict'
import React, {useState} from 'react'
import PropTypes from 'prop-types'
import {ParticipantResource} from '../../ResourceTypes'
import {updateParticipant} from '../../Share/ParticipantXHR'
import Input from '../../Share/Form/Elements/Input'

type Props = {
  currentParticipant: ParticipantResource
  close: () => void
  load: () => void
}
const EditForm = ({currentParticipant, close, load}: Props) => {
  const [firstName, setFirstName] = useState(currentParticipant.firstName)
  const [lastName, setLastName] = useState(currentParticipant.lastName)

  const save = () => {
    updateParticipant(currentParticipant.id, firstName, lastName).then(() => {
      load()
      close()
    })
  }

  return (
    <div>
      <div className="form-group">
        <label>First/Chosen name</label>
        <Input
          {...{
            value: firstName,
            update: setFirstName,
            name: 'firstName',
            allowEmpty: true,
          }}
        />
      </div>

      <div className="form-group">
        <label>Last name</label>
        <Input
          {...{
            value: lastName,
            update: setLastName,
            name: 'lastName',
            allowEmpty: true,
          }}
        />
      </div>
      <div>
        <button className="btn btn-outline-danger float-right" onClick={close}>
          Cancel
        </button>
        <button className="btn btn-success float-right mr-1" onClick={save}>
          Save
        </button>
      </div>
    </div>
  )
}

EditForm.propTypes = {currentParticipant: PropTypes.object}
export default EditForm
