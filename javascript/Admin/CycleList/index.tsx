'use strict'
import React, {useState, useEffect, Fragment} from 'react'
import PropTypes from 'prop-types'
import {getList} from '../../Share/XHR'
import {createRoot} from 'react-dom/client'
import Loading from '../../Share/Loading'
import AwardSelect from './AwardSelect'
import Listing from './Listing'
import {CycleResource} from '../../ResourceTypes'

declare const defaultAwardId: number

interface AwardBasic {
  id: number
  title: string
  cycleTerm: string
}

const getAward = (awardId: number, awardList: AwardBasic[]): AwardBasic => {
  let award = {id: 0, title: '', cycleTerm: ''}
  awardList.forEach((element) => {
    if (awardId === element.id) {
      award = element
    }
  })
  return award
}

const CycleList = ({defaultAwardId}: {defaultAwardId: number}) => {
  const [awardId, setAwardId] = useState(defaultAwardId)
  const [awardList, setAwardList] = useState<AwardBasic[]>([])
  const [errorMessage, setErrorMessage] = useState('')
  const [cycleListing, setCycleListing] = useState<CycleResource[]>([])
  const [loading, setLoading] = useState(false)
  const [award, setAward] = useState<AwardBasic>({
    id: 0,
    title: '',
    cycleTerm: '',
  })

  useEffect(() => {
    loadTitles()
  }, [])

  const loadTitles = () => {
    const controller = new AbortController()
    const {signal} = controller
    const url = './award/Admin/Award/basic'
    const handleSuccess = (data: AwardBasic[]) => {
      setAwardList(data)
      if (defaultAwardId === 0) {
        setAwardId(data[0].id)
      }
    }
    const handleError = () => {
      setErrorMessage('Could not retrieve awards')
    }
    getList({url, handleSuccess, handleError, signal})
  }

  useEffect(() => {
    if (awardId > 0) {
      loadList()
    }
  }, [awardId])

  const loadList = () => {
    setLoading(true)
    const controller = new AbortController()
    const {signal} = controller
    const url = `award/Admin/Cycle/?awardId=${awardId}`
    const handleSuccess = (data: CycleResource[]) => {
      setAward(getAward(awardId, awardList))
      setCycleListing(data)
      setLoading(false)
    }
    getList({url, handleSuccess, signal})
  }

  useEffect(() => {
    if (awardList) {
      setAward(getAward(awardId, awardList))
    }
  }, [awardList])

  let content
  if (awardList === null) {
    content = <Loading things="awards" />
  } else {
    if (awardList.length === 0) {
      content = <div>No awards have been created.</div>
    } else {
      content = (
        <Fragment>
          <div className="row">
            <div className="col-4">
              <a
                className="btn btn-success"
                href={`./award/Admin/Cycle/create?awardId=${awardId}`}>
                Create new cycle
              </a>
            </div>
            <div className="col-2">
              <span className="float-left">Change award:</span>
            </div>
            <div className="col-4">
              <AwardSelect {...{awardId, setAwardId, awardList}} />
            </div>
          </div>
          <hr />
          {loading ? (
            <Loading things="cycles" />
          ) : (
            <Listing reload={loadList} {...{cycleListing}} />
          )}
        </Fragment>
      )
    }
  }

  return (
    <div>
      <h2>
        Cycles for {award.cycleTerm} {award.title}
      </h2>
      {errorMessage.length > 0 && (
        <div className="alert alert-danger">{errorMessage}</div>
      )}
      {content}
    </div>
  )
}

CycleList.propTypes = {defaultAwardId: PropTypes.number}

const container = document.getElementById('CycleList') as HTMLElement
const root = createRoot(container)

root.render(<CycleList defaultAwardId={defaultAwardId} />)
