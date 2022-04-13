'use strict'
import React, {useState, useEffect} from 'react'
import PropTypes from 'prop-types'
import ReactDOM from 'react-dom'

const SignUpForm = () => {
  const [email, setEmail] = useState()
  const [password1, setPassword1] = useState('')
  const [password2, setPassword2] = useState('')
  const [emailError, setEmailError] = useState(false)
  const [passwordError, setPasswordError] = useState(false)

  const checkAndSave = () => {
    if (emailError || passwordError) {
      return
    }
  }

  const matchEmail = () => {
    setEmailError((email.match(/@/g) || []).length !== 1)
  }

  const checkPassword = () => {
    setPasswordError(password1 !== password2 || password1.length < 6)
  }

  return (
    <div>
      <div className="form-group row">
        <label className="col-sm-4 col-form-label" htmlFor="email">
          Your email address
        </label>
        <div className="col-sm-8">
          <input
            type="text"
            className="form-control"
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
          <input
            type="password"
            className="form-control"
            name="email"
            onBlur={checkPassword}
            value={password1}
            onChange={(e) => setPassword1(e.target.value)}
          />
          {passwordError ? (
            <span className="badge badge-danger">
              Passwords must match, be longer than 6 characters.
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
            type="password"
            className="form-control"
            name="email"
            onBlur={checkPassword}
            value={password2}
            onChange={(e) => setPassword2(e.target.value)}
          />
        </div>
      </div>
      <button className="btn btn-success btn-block" onClick={checkAndSave}>
        Create account
      </button>
    </div>
  )
}

SignUpForm.propTypes = {}

ReactDOM.render(<SignUpForm />, document.getElementById('SignUpForm'))
