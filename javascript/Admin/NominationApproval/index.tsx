'use strict'
import React, {useState, useEffect} from 'react'
import {createRoot} from 'react-dom/client'
import Loading from '../../Share/Loading'
import Grid from './Grid'
import {nominationApprovalList} from '../../Share/NominationXHR'

declare const templateValue: string

const NominationApproval = () => {
  const [nominations, setNominations] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    loadApprovals()
  }, [])

  const loadApprovals = () => {
    setLoading(true)
    nominationApprovalList().then((response) => {
      setNominations(response.data)
      setLoading(false)
    })
  }
  if (loading) {
    return <Loading things="nominations needing approval" />
  } else if (nominations.length === 0) {
    return (
      <p>
        <em>No nominations need approval.</em>
      </p>
    )
  } else {
    return <Grid {...{nominations}} />
  }
}

const container = document.getElementById('NominationApproval') as HTMLElement
const root = createRoot(container)
root.render(<NominationApproval />)
