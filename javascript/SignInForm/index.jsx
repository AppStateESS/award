'use strict'
import React, {useState, useEffect} from 'react'
import {signInPost} from '../Share/ParticipantXHR'
import {createRoot} from 'react-dom/client'

const SignInForm = () => {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [emailError, setEmailError] = useState(false)
  const [passwordError, setPasswordError] = useState(false)
  const [signInResult, setSignInResult] = useState('')

  const signIn = () => {
    const emailErr = email.length === 0
    const passwordErr = password.length === 0
    setEmailError(emailErr)
    setPasswordError(passwordErr)
    if (emailErr || passwordErr) {
      return
    } else {
      signInPost(email, password)
        .then((response) => {
          if (response.data) {
            if (response.data.success) {
              location.href = './award/Participant/Participant/dashboard'
            } else {
              setSignInResult(response.data.message)
            }
          } else {
            setSignInResult('No response from server. Please try again.')
          }
        })
        .catch(() => {
          //location.href = './award/User/Participant/error'
        })
    }
  }

  const checkEnter = (key) => {
    if (key.which === 13 && checkPassword() && checkEmail()) {
      signIn()
    }
  }

  const checkPassword = () => {
    const check = password.length === 0
    setPasswordError(check)
    return !check
  }

  const checkEmail = () => {
    const check = email.length === 0
    setEmailError(check)
    return !check
  }

  return (
    <div>
      {signInResult.length > 0 ? (
        <div className="alert alert-danger">{signInResult}</div>
      ) : null}
      <div className="form-group row">
        <div className="col-4 col-form-label">Email address</div>
        <div className="col">
          <input
            type="text"
            name="email"
            value={email}
            onBlur={checkEmail}
            className="form-control"
            onChange={(e) => {
              setEmail(e.target.value)
            }}
          />
          {emailError ? (
            <span className="badge badge-danger">
              Please enter your email address
            </span>
          ) : null}
        </div>
      </div>
      <div className="form-group row">
        <div className="col-4 col-form-label">Password</div>
        <div className="col">
          <input
            type="password"
            name="password"
            onBlur={checkPassword}
            value={password}
            className="form-control"
            onChange={(e) => {
              setPassword(e.target.value)
            }}
            onKeyDown={checkEnter}
          />
          {passwordError ? (
            <span className="badge badge-danger">
              Please enter your password
            </span>
          ) : null}
          <a className="small" href="./award/User/Participant/forgotPassword">
            I forgot my password
          </a>
        </div>
      </div>
      <div className="text-center">
        <button
          className="btn btn-primary"
          onClick={signIn}
          disabled={emailError || passwordError}>
          Sign In
        </button>
      </div>
    </div>
  )
}

const container = document.getElementById('SignInForm')
const root = createRoot(container)
root.render(<SignInForm />)
