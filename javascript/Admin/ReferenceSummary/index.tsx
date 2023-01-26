'use strict'
import React, {useState, useEffect, useCallback} from 'react'
import {createRoot} from 'react-dom/client'
import {ReferenceResource} from '../../ResourceTypes'
import Loading from '../../Share/Loading'
import Modal from '../../Share/Modal'
import {reasonCompleted} from '../../Share/Reference'
import {referenceReminderAllowed} from '../../Share/Reminder'
import {
  getNominationReferences,
  sendReferenceReasonReminder,
} from '../../Share/ReferenceXHR'
import Reason from './Reason'
import ReferenceText from './ReferenceText'

declare const nominationId: number

const ReferenceSummary = () => {
  const [referenceList, setReferenceList] = useState<ReferenceResource[]>([])
  const [loading, setLoading] = useState(true)
  useEffect(() => {
    load()
  }, [])
  const [showText, setShowText] = useState(false)
  const [referenceText, setReferenceText] = useState('')

  const showReasonText = (reasonText: string) => {
    setShowText(true)
    setReferenceText(reasonText)
  }

  const load = () => {
    getNominationReferences(nominationId).then((response) => {
      setReferenceList(response.data)
      setLoading(false)
    })
  }

  const remind = (key: number) => {
    const reference = referenceList[key]
    sendReferenceReasonReminder(reference.id, 'Admin').then((response) => {
      if (response.data.success) {
        reference.lastReminder = 'today'
        referenceList[key] = reference
        setReferenceList([...referenceList])
      }
    })
  }

  const closeModal = useCallback(() => {
    setShowText(false)
  }, ['showText'])

  if (loading) {
    return <Loading things="references" />
  } else if (referenceList.length === 0) {
    return <div>No references accepted.</div>
  } else {
    return (
      <div>
        <h3>References</h3>
        <Modal
          title="Text endorsement"
          show={showText}
          size="lg"
          close={closeModal}>
          <ReferenceText text={referenceText} />
        </Modal>
        <table className="table table-striped">
          <thead>
            <tr>
              <th>Reference name</th>
              <th>Email</th>
              <th>Endorsement</th>
              <th>Last reminder</th>
            </tr>
          </thead>
          <tbody>
            {referenceList.map((value, key) => {
              let remindButton
              if (!reasonCompleted(value)) {
                if (referenceReminderAllowed(value.lastReminder)) {
                  remindButton = (
                    <span
                      className="badge badge-primary"
                      style={{cursor: 'pointer'}}
                      onClick={() => {
                        remind(key)
                      }}>
                      Send reminder
                    </span>
                  )
                } else {
                  remindButton = (
                    <span
                      title={`Last sent ${value.lastReminder}`}
                      className="badge badge-info text-white">
                      Too soon
                    </span>
                  )
                }
              }
              return (
                <tr key={`ref-${value.id}`}>
                  <td>
                    {value.participantFirstName} {value.participantLastName}
                  </td>
                  <td>
                    <a href={`mailto:${value.participantEmail}`}>
                      {value.participantEmail}
                    </a>
                  </td>
                  <td>
                    <Reason
                      reference={value}
                      showReasonText={() => showReasonText(value.reasonText)}
                    />
                  </td>
                  <td>{remindButton}</td>
                </tr>
              )
            })}
          </tbody>
        </table>
      </div>
    )
  }
}

const container = document.getElementById('ReferenceSummary') as HTMLElement
const root = createRoot(container)
root.render(<ReferenceSummary />)
