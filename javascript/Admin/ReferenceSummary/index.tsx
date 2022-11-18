'use strict'
import React, {useState, useEffect} from 'react'
import {createRoot} from 'react-dom/client'
import {ReferenceResource} from '../../ResourceTypes'
import Loading from '../../Share/Loading'
import {getNominationReferences} from '../../Share/ReferenceXHR'
import Reason from './Reason'

declare const nominationId: number

const ReferenceSummary = () => {
  const [referenceList, setReferenceList] = useState<ReferenceResource[]>([])
  const [loading, setLoading] = useState(true)
  useEffect(() => {
    load()
  }, [])

  const showReasonText = (reasonText: string) => {
    console.log(reasonText)
  }

  const load = () => {
    getNominationReferences(nominationId).then((response) => {
      setReferenceList(response.data)
      setLoading(false)
    })
  }
  if (loading) {
    return <Loading things="references" />
  } else if (referenceList.length === 0) {
    return <div>No references accepted.</div>
  } else {
    return (
      <div>
        <h3>References</h3>
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
            {referenceList.map((value) => {
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
                  <td>{value.lastReminder}</td>
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
