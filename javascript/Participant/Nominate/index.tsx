'use strict'
import React, {useState} from 'react'
import {createRoot} from 'react-dom/client'
import {fullAwardTitle} from '../../Share/Cycle'
import {AwardResource, CycleResource} from '../../ResourceTypes'

declare const cycle: CycleResource
declare const award: AwardResource
declare const match: boolean

const Nominate = () => {
  const [email, setEmail] = useState('')
  const [firstName, setFirstName] = useState('')
  const [lastName, setLastName] = useState('')

  const preventPost =
    email.length === 0 || firstName.length === 0 || lastName.length === 0

  const updateEmail = (arg: string) => {
    setEmail(arg)
  }

  return (
    <div>
      <div className="row">
        <div className="col-sm-8 col-md-6 mx-auto">
          <div className="card">
            <div className="card-header">
              <h2 className="m-0">{fullAwardTitle(award, cycle)} nomination</h2>
            </div>
            <div className="card-body">
              <div className="row">
                <div className="col-6">
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
                </div>
                <div className="col-6">
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
                </div>
              </div>
              <div className="form-group">
                <label htmlFor="email">Email address</label>
                <input
                  type="text"
                  className="form-control"
                  name="email"
                  value={email}
                  onChange={(e) => updateEmail(e.target.value)}
                />
              </div>
            </div>
            <div className="card-footer text-muted">
              <button
                className="btn btn-success btn-block"
                disabled={preventPost}>
                Nominate!
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

const container = document.getElementById('Nominate') as HTMLElement
const root = createRoot(container)
root.render(<Nominate />)
