'use strict'
import React, {useState, useEffect, Fragment} from 'react'
import PropTypes from 'prop-types'
import {createRoot} from 'react-dom/client'
import {getList} from '../../Share/XHR'
import {NominationResource} from '../../ResourceTypes'

declare const cycleId: number

interface NominationWithName extends NominationResource {
  firstName: string
  lastName: string
  email: string
}

const NominationList = ({nominations}: {nominations: NominationWithName[]}) => {
  return (
    <Fragment>
      {nominations.map((value) => {
        return (
          <div key={`nominations-${value.id}`}>
            {value.firstName} {value.lastName}
          </div>
        )
      })}
    </Fragment>
  )
}

NominationList.propTypes = {nominations: PropTypes.array}

const Nominations = () => {
  const [nominations, setNominations] = useState<NominationWithName[]>()

  useEffect(() => {
    const controller = new AbortController()
    const {signal} = controller
    load(signal)
    return () => {
      controller.abort()
    }
  }, [])
  const load = (signal: AbortSignal) => {
    const params = {
      url: './award/Admin/Nomination/?cycleId=' + cycleId,
      signal,
      handleSuccess: (data: NominationWithName[]) => {
        setNominations(data)
      },
    }
    getList(params)
  }

  let content = <div>Loading nominations...</div>
  if (nominations !== undefined) {
    if (nominations.length === 0) {
      content = (
        <div className="text-danger">No nominations for this award.</div>
      )
    } else {
      content = <NominationList nominations={nominations} />
    }
  }
  return (
    <div className="card">
      <div className="card-header">
        <h4 className="m-0">Nominations</h4>
      </div>
      <div className="card-body">{content}</div>
    </div>
  )
}

const container = document.getElementById('Nominations') as HTMLElement
const root = createRoot(container)
root.render(<Nominations />)
