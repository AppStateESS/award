'use strict'
import React from 'react'
import {createRoot} from 'react-dom/client'
import {DocumentResource, ReasonResource} from '../../ResourceTypes'
import ReasonForm from './ReasonForm'

declare const awardTitle: string
declare const currentDocument: DocumentResource
declare const maxsize: number
declare const nominatedName: string
declare const reason: ReasonResource

const ReferenceReasonForm = () => {
  return (
    <div>
      <h3>
        {nominatedName} for the {awardTitle} nomination
      </h3>
      <hr />
      <ReasonForm
        currentDocument={currentDocument}
        maxsize={maxsize}
        nominatedName={nominatedName}
        reason={reason}
      />
    </div>
  )
}

const container = document.getElementById('ReferenceReasonForm') as HTMLElement
const root = createRoot(container)
root.render(<ReferenceReasonForm />)
