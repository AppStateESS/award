'use strict'
import React, {useState, useEffect} from 'react'
import PropTypes from 'prop-types'
import {ParticipantResource} from '../../ResourceTypes'

type Props = {currentParticipant: ParticipantResource | undefined}

const BanForm = ({currentParticipant}: Props) => {
  if (!currentParticipant) {
    return <div className="alert alert-danger">Missing participant</div>
  }
  return (
    <div className="alert alert-danger">
      <p>
        Are you sure you want to ban{' '}
        {currentParticipant.firstName || currentParticipant.lastName ? (
          <span>currentParticipant.firstName currentParticipant.lastName</span>
        ) : null}
        {currentParticipant.email}?
      </p>
      <p>
        The ban will prevent them from participating in any future award
        process.
      </p>
    </div>
  )
}

BanForm.propTypes = {currentParticipant: PropTypes.object}
export default BanForm
