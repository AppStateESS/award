'use strict'
import React, {useState, useEffect} from 'react'
import PropTypes from 'prop-types'
import {Input} from '../../Share/Form/Form'
import AsyncSelect from 'react-select/async'

type Props = {}
const Form = ({}: Props) => {
  const [email, setEmail] = useState('')

  const getOptions = (value) => {
    return [
      {value: 1, label: 'one'},
      {value: 2, label: 'two'},
    ]
  }

  const updateEmail = (email) => {
    console.log(email)
  }

  const loadOptions = (search, getOptions) => {
    return getOptions()
  }

  return (
    <div>
      <p>
        Enter the email address or name of the person you wish to serve as a
        judge.
      </p>
      <p>Be aware, the judge request may be refused.</p>
      <AsyncSelect loadOptions={loadOptions} onInputChange={updateEmail} />
    </div>
  )
}

Form.propTypes = {}
export default Form
