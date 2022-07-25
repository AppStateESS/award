'use strict'
import React, {useState, useEffect, useRef} from 'react'
import {resetPassword} from '../Share/ParticipantXHR'
import {createRoot} from 'react-dom/client'

type Props = {}

declare const participantId: number
declare const hash: string

const ResetPassword = () => {
  const [password1, setPassword1] = useState('')
  const [password2, setPassword2] = useState('')
  const [passwordError, setPasswordError] = useState(false)
  const [showPassword, setShowPassword] = useState(false)

  const firstPassword = useRef<HTMLInputElement>(null)

  useEffect(() => {
    if (firstPassword.current) {
      firstPassword.current.focus()
    }
  }, [])

  const togglePassword = () => {
    setShowPassword(!showPassword)
  }

  const checkAndSave = () => {
    resetPassword(participantId, password1, hash).then((response) => {
      console.log(response.data)
    })
  }

  const checkPassword = () => {
    setPasswordError(password1 !== password2 || password1.length < 6)
  }

  const disableSave =
    passwordError || password1.length === 0 || password2.length === 0

  return (
    <div className="row">
      <div className="col-sm-8 col-md-6 mx-auto">
        <div className="card">
          <div className="card-header">
            <h2 className="m-0">Change your password</h2>
          </div>
          <div className="card-body">
            <p className="card-text">
              Please enter your new password twice below. It should be longer
              than six characters.
            </p>
            <div className="form-group row">
              <div className="col-sm-4">New password</div>
              <div className="col-sm-8">
                <div className="input-group mb-3">
                  <input
                    type={showPassword ? 'text' : 'password'}
                    ref={firstPassword}
                    tabIndex={1}
                    className="form-control"
                    name="password1"
                    onBlur={checkPassword}
                    value={password1}
                    onChange={(e) => setPassword1(e.target.value)}
                  />
                  <div className="input-group-append">
                    <button
                      tabIndex={3}
                      className="btn btn-outline-dark"
                      onClick={togglePassword}>
                      <i className="fas fa-eye"></i>
                    </button>
                  </div>
                  {passwordError ? (
                    <span className="badge badge-danger">
                      Passwords must match and exceed 6 characters.
                    </span>
                  ) : null}
                </div>
              </div>
            </div>
            <div className="form-group row">
              <label className="col-sm-4 col-form-label" htmlFor="password2">
                Repeat password
              </label>
              <div className="col-sm-8">
                <input
                  type={showPassword ? 'text' : 'password'}
                  tabIndex={2}
                  className="form-control"
                  name="password2"
                  onBlur={checkPassword}
                  value={password2}
                  onChange={(e) => setPassword2(e.target.value)}
                />
              </div>
            </div>
            <button
              className="btn btn-success btn-block"
              tabIndex={4}
              onClick={checkAndSave}
              disabled={disableSave}>
              Create account
            </button>
          </div>
        </div>
      </div>
    </div>
  )
}

ResetPassword.propTypes = {}

const container = document.getElementById('ResetPassword') as HTMLElement
const root = createRoot(container)
root.render(<ResetPassword />)
