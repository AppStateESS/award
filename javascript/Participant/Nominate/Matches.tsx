'use strict'
import React, {useState, useEffect, useRef} from 'react'
import PropTypes from 'prop-types'
import {searchNominees} from '../../Share/ParticipantXHR'
import './style.css'
import {ParticipantResource} from '../../ResourceTypes'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'

type Props = {
  nominateParticipant: (participantId: number) => void
  cycleId: number
}
const Matches = ({nominateParticipant, cycleId}: Props) => {
  const [matchedParticipants, setMatchedParticipants] = useState<
    ParticipantResource[]
  >([])
  const [match, setMatch] = useState('')
  const [selected, setSelected] = useState<number | null>(null)
  const [searchIcon, setSearchIcon] = useState(false)

  const matchTimer = useRef<ReturnType<typeof setTimeout>>()

  const reset = () => {
    setMatch('')
    setMatchedParticipants([])
    setSelected(null)
  }

  const select = (index: number) => {
    if (selected === index) {
      setSelected(null)
    } else {
      setSelected(index)
    }
  }

  const selectedName = () => {
    if (selected !== null) {
      const participant = matchedParticipants[selected]
      return `${participant.firstName} ${participant.lastName}`
    }
  }

  useEffect(() => {
    if (match.length > 3) {
      setSearchIcon(true)
      clearTimeout(matchTimer.current)
      matchTimer.current = setTimeout(() => {
        searchNominees(match, cycleId).then((response) => {
          setMatchedParticipants(response.data)
          setSearchIcon(false)
          clearTimeout(matchTimer.current)
        })
      }, 1000)
    } else {
      setSearchIcon(false)
      clearTimeout(matchTimer.current)
      setMatchedParticipants([])
    }
  }, [match])

  let button
  let listing
  if (matchedParticipants.length > 0) {
    if (selected !== null) {
      button = (
        <button
          className="btn btn-primary btn-block"
          onClick={() => {
            nominateParticipant(matchedParticipants[selected].id)
          }}>
          Nominate {selectedName()}
        </button>
      )
    }

    listing = matchedParticipants.map(
      (value: ParticipantResource, index: number) => {
        const highlightSelected = index === selected ? ' selected' : ''

        return (
          <div
            className={`select-row${highlightSelected}`}
            key={`participant-${value.id}`}
            onClick={() => select(index)}>
            {value.firstName} {value.lastName} - {value.email}
          </div>
        )
      }
    )
  }

  return (
    <div className="match-choice">
      <div className="input-group m-2">
        <input
          type="text"
          className="form-control"
          name="match"
          placeholder="Search for participants"
          value={match}
          onChange={(e) => setMatch(e.target.value)}
        />
        <div className="input-group-append">
          <button className="btn btn-danger" type="button" onClick={reset}>
            Clear
          </button>
        </div>
      </div>
      <div className="small">Can&apos;t find participant?</div>
      {searchIcon && (
        <div className="text-secondary m-1 text-center">
          <FontAwesomeIcon icon={['fas', 'spinner']} size="lg" spin />
          &nbsp;Searching...
        </div>
      )}
      {listing}
      {button}
    </div>
  )
}

Matches.propTypes = {nominateParticipant: PropTypes.func}
export default Matches
