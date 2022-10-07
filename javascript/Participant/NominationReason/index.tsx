'use strict'
import React, {useState, useEffect} from 'react'
import {createRoot} from 'react-dom/client'
import {
  AwardResource,
  CycleResource,
  ParticipantResource,
} from '../../ResourceTypes'
import ReasonForm from './ReasonForm'
import {fullAwardTitle} from '../../Share/Cycle'

declare const participant: ParticipantResource
declare const award: AwardResource
declare const cycle: CycleResource
declare const maxsize: number

const NominationReason = () => {
  const fullName = (
    <span>
      {participant.firstName} {participant.lastName}
    </span>
  )
  return (
    <div>
      <h3>
        Nominate {fullName} for the {fullAwardTitle(award, cycle)}
      </h3>
      <hr />
      {award.nominationReasonRequired && (
        <ReasonForm firstName={participant.firstName} maxsize={maxsize} />
      )}
    </div>
  )
}

const container = document.getElementById('NominationReason') as HTMLElement
const root = createRoot(container)
root.render(<NominationReason />)