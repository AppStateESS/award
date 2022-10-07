'use strict'
import React, {useState} from 'react'
import {createRoot} from 'react-dom/client'
import {AwardResource, CycleResource} from '../../ResourceTypes'
import {fullAwardTitle} from '../../Share/Cycle'

import Matches from './Matches'

declare const cycle: CycleResource
declare const award: AwardResource

const Nominate = () => {
  const [email, setEmail] = useState('')
  const [firstName, setFirstName] = useState('')
  const [lastName, setLastName] = useState('')

  const preventPost =
    email.length === 0 || firstName.length === 0 || lastName.length === 0

  const nominateParticipant = (participantId: number) => {
    if (participantId > 0) {
      location.href =
        './award/Participant/Nomination/nominateParticipant?' +
        `participantId=${participantId}` +
        `&cycleId=${cycle.id}`
    }
  }

  return (
    <div>
      <h2>{fullAwardTitle(award, cycle)} nomination</h2>
      <hr />
      <div className="row">
        <div className="col-6">
          <h3>Enter nominee information</h3>
          <div className="form-group">
            <label htmlFor="firstName">First/Chosen name</label>
            <input
              type="text"
              className="form-control"
              name="firstName"
              value={firstName}
              onChange={(e) => setFirstName(e.target.value)}
            />
          </div>

          <div className="form-group">
            <label htmlFor="lastName">Last name</label>
            <input
              type="text"
              className="form-control"
              name="lastName"
              value={lastName}
              onChange={(e) => setLastName(e.target.value)}
            />
          </div>

          <div className="form-group">
            <label htmlFor="email">Email address</label>
            <input
              type="text"
              className="form-control"
              name="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
          </div>
          <button className="btn btn-success btn-block" disabled={preventPost}>
            Nominate!
          </button>
        </div>
        <div className="col-6">
          <h3>Matching participants</h3>
          <Matches
            nominateParticipant={nominateParticipant}
            cycleId={cycle.id}
          />
        </div>
      </div>
    </div>
  )
}

const container = document.getElementById('Nominate') as HTMLElement
const root = createRoot(container)
root.render(<Nominate />)
