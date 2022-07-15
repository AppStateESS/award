'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {AuthAvailable} from './Interface'

const iconOff = (
  <FontAwesomeIcon
    className="text-danger"
    icon={['fas', 'toggle-off']}
    size="2x"
  />
)

const iconOn = (
  <FontAwesomeIcon
    className="text-success"
    icon={['fas', 'toggle-on']}
    size="2x"
  />
)

const Authentication = ({
  authAvailable,
  toggleAuth,
}: {
  authAvailable: AuthAvailable[]
  toggleAuth: (filename: string, toggle: boolean) => void
}) => {
  const rows = authAvailable.map((value) => {
    return (
      <tr key={`auth-${value.filename}`}>
        <td>{value.title}</td>
        <td>
          <a
            style={{cursor: 'pointer'}}
            onClick={() => {
              toggleAuth(value.filename, value.enabled)
            }}>
            {value.enabled ? iconOn : iconOff}
          </a>
        </td>
      </tr>
    )
  })
  return (
    <table className="table table-striped">
      <tbody>{rows}</tbody>
    </table>
  )
}

Authentication.propTypes = {
  authAvailable: PropTypes.array,
  toggleAuth: PropTypes.func,
}

export default Authentication
