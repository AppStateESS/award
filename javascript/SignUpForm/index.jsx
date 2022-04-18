'use strict'
import React, {useState, useEffect, useRef} from 'react'
import {saveNewParticipant} from '../Share/ParticipantXHR'
import {createRoot} from 'react-dom/client'

const SignUpForm = () => {
  const [email, setEmail] = useState('')
  const [password1, setPassword1] = useState('')
  const [password2, setPassword2] = useState('')
  const [emailError, setEmailError] = useState(false)
  const [passwordError, setPasswordError] = useState(false)
  const [showPassword, setShowPassword] = useState(false)

  const tab1 = useRef()

  useEffect(() => {
    tab1.current.focus()
  }, [])

  const checkAndSave = () => {
    if (emailError || passwordError || email.length === 0) {
      return
    }
    saveNewParticipant(email, password1).then(() => {
      location.href = './award/User/Participant/emailSent'
    })
  }

  const matchEmail = () => {
    const search = (email.match(/@/g) || []).length
    const correctEmail = search === 1 && email.length > 2
    setEmailError(!correctEmail)
  }

  const checkPassword = () => {
    setPasswordError(password1 !== password2 || password1.length < 6)
  }

  const togglePassword = () => {
    setShowPassword(!showPassword)
  }

  const disableSave =
    emailError ||
    passwordError ||
    password1.length === 0 ||
    password2.length === 0 ||
    email.length === 0

  return (
    <div>
      <div className="form-group row">
        <label className="col-sm-4 col-form-label" htmlFor="email">
          Your email address
        </label>
        <div className="col-sm-8">
          <input
            type="text"
            tabIndex="0"
            className="form-control"
            ref={tab1}
            name="email"
            value={email}
            onBlur={matchEmail}
            onChange={(e) => setEmail(e.target.value)}
          />
          {emailError ? (
            <span className="badge badge-danger">
              Email address needs fixing.
            </span>
          ) : null}
        </div>
      </div>
      <div className="form-group row">
        <label className="col-sm-4 col-form-label" htmlFor="password1">
          Your password
        </label>
        <div className="col-sm-8">
          <div className="input-group mb-3">
            <input
              type={showPassword ? 'text' : 'password'}
              tabIndex="1"
              className="form-control"
              name="password1"
              onBlur={checkPassword}
              value={password1}
              onChange={(e) => setPassword1(e.target.value)}
            />
            <div className="input-group-append">
              <button className="btn btn-outline-dark" onClick={togglePassword}>
                <i className="fas fa-eye"></i>
              </button>
            </div>
          </div>

          {passwordError ? (
            <span className="badge badge-danger">
              Passwords must match and exceed 6 characters.
            </span>
          ) : null}
        </div>
      </div>
      <div className="form-group row">
        <label className="col-sm-4 col-form-label" htmlFor="password2">
          Repeat password
        </label>
        <div className="col-sm-8">
          <input
            type={showPassword ? 'text' : 'password'}
            tabIndex="2"
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
        onClick={checkAndSave}
        disabled={disableSave}>
        Create account
      </button>
    </div>
  )
}

const container = document.getElementById('SignUpForm')
const root = createRoot(container)
root.render(<SignUpForm />)
