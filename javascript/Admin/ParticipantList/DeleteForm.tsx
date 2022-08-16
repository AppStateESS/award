'use strict'
import React, {useState, useEffect} from 'react'
import PropTypes from 'prop-types'
import {ParticipantResource} from '../../ResourceTypes'

type Props = {currentParticipant: ParticipantResource | undefined}
const DeleteForm = ({currentParticipant}: Props) => {
  return <div>DeleteForm is ready for content.</div>
}

DeleteForm.propTypes = {currentParticipant: PropTypes.object}
export default DeleteForm
