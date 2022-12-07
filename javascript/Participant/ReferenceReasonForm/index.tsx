'use strict'
import React from 'react'
import {createRoot} from 'react-dom/client'
import {
  AwardResource,
  CycleResource,
  DocumentResource,
  ParticipantResource,
  ReferenceResource,
} from '../../ResourceTypes'
import ReasonForm from './ReasonForm'
import {fullAwardTitle} from '../../Share/Cycle'

declare const participant: ParticipantResource
declare const award: AwardResource
declare const reference: ReferenceResource
declare const cycle: CycleResource
declare const maxsize: number
declare const currentReasonDocument: DocumentResource

const ReferenceReasonForm = () => {
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
      <ReasonForm
        currentReasonDocument={currentReasonDocument}
        maxsize={maxsize}
        reference={reference}
        participant={participant}
      />
    </div>
  )
}

const container = document.getElementById('ReferenceReasonForm') as HTMLElement
const root = createRoot(container)
root.render(<ReferenceReasonForm />)
