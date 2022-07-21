'use strict'
import React, {Fragment, useState, useEffect} from 'react'
import {createRoot} from 'react-dom/client'

const ForgotPassword = () => {
  const [email, setEmail] = useState('')
  const [emailError, setEmailError] = useState(false)

  useEffect(() => {
    if (email.length === 0) {
      setEmailError(false)
      return
    }
    setEmailError(email.match(/.+@.+/) === null)
  }, [email])

  return (
    <div className="row">
      <div className="col-sm-10 col-md-6 mx-auto">
        <div className="card">
          <div className="card-header bg-primary">
            <h2 className="m-0 text-white">Forgot my Password</h2>
          </div>
          <div className="card-body">
            <form method="post" action="award/User/Participant/forgotPassword">
              <div className="row">
                <div className="col-sm-6">Enter your email address:</div>
                <div className="col-sm-6">
                  <input
                    type="text"
                    name="email"
                    className="form-control"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                  />
                  {emailError && (
                    <span className="small text-danger">
                      Email format incorrect
                    </span>
                  )}
                </div>
              </div>
              <hr />
              <button
                className="btn btn-success btn-block"
                type="submit"
                disabled={email.length === 0 || emailError}>
                Send me a password change email
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  )
}

const container = document.getElementById('ForgotPassword') as HTMLElement
const root = createRoot(container)
root.render(<ForgotPassword />)
