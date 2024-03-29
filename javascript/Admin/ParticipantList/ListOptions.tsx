'use strict'
import React, {useState, Dispatch, SetStateAction} from 'react'
import PropTypes from 'prop-types'

const ListOptions = ({
  setModalContentType,
  participantKey,
  loadParticipant,
  trusted,
}: {
  setModalContentType: Dispatch<SetStateAction<string>>
  participantKey: number
  loadParticipant: (key: number) => void
  trusted: number | boolean
}) => {
  const [option, setOption] = useState('default')

  const trustLabel = trusted === 1 ? 'Untrust' : 'Trust'

  return (
    <select
      value={option}
      onChange={(e) => {
        setOption('default')
        setModalContentType(e.target.value)
        loadParticipant(participantKey)
      }}>
      <option value="default"></option>
      <option value="edit">Edit</option>
      <option value="trust">{trustLabel}</option>
      <option value="ban">Ban</option>
      <option value="delete">Delete</option>
    </select>
  )
}

ListOptions.propTypes = {setModalContentType: PropTypes.func}
export default ListOptions
