'use strict'
import React, {useState, useEffect} from 'react'
import PropTypes from 'prop-types'
import {getList} from '../../Share/XHR'
import {createRoot} from 'react-dom/client'
import {useTransition} from 'transition-hook'
import Loading from '../../Share/Loading'
import AwardSelect from './AwardSelect'
import Listing from './Listing'
import {CycleResource} from '../../ResourceTypes'

declare const defaultAwardId : number

interface AwardTitle {
  id: number
  title: string
}

const getAwardTitle = (
  awardId: number,
  awardList: Array<AwardTitle>
): string => {
  if (awardId === 0) {
    return ''
  }

  let awardTitle = ''
  awardList.forEach((element) => {
    if (awardId === element.id) {
      awardTitle = element.title
    }
  })
  return awardTitle
}

const CycleList = ({defaultAwardId}: {defaultAwardId: number}) => {
  const [awardId, setAwardId] = useState(defaultAwardId)
  const [awardList, setAwardList] = useState<AwardTitle[] | null>(null)
  const [errorMessage, setErrorMessage] = useState('')
  const [cycleListing, setCycleListing] = useState<CycleResource[]>([])
  const [loading, setLoading] = useState(false)
  const [awardTitle, setAwardTitle] = useState('')
  const {stage, shouldMount} = useTransition(errorMessage.length > 0, 500)

  useEffect(() => {
    const controller = new AbortController()
    const {signal} = controller
    const url = './award/Admin/Award/titles'
    const handleSuccess = (data: AwardTitle[]) => {
      setAwardList(data)
      if (defaultAwardId === 0) {
        setAwardId(data[0].id)
      }
    }
    const handleError = () => {
      setErrorMessage('Could not retrieve awards')
    }
    getList({url, handleSuccess, handleError, signal})
  }, [])

  useEffect(() => {
    if (awardId > 0) {
      setLoading(true)
      const controller = new AbortController()
      const {signal} = controller
      const url = `award/Admin/Cycle/?awardId=${awardId}`
      const handleSuccess = (data: CycleResource[]) => {
        setCycleListing(data)
        setLoading(false)
      }
      getList({url, handleSuccess, signal})
    }
  }, [awardId])

  useEffect(() => {
    if (awardList) {
      setAwardTitle(getAwardTitle(awardId, awardList))
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
        <div>
          <AwardSelect {...{awardId, setAwardId, awardList}} />
          <hr />
          {loading ? (
            <Loading things="cycles" />
          ) : (
            <Listing {...{cycleListing}} />
          )}
        </div>
      )
    }
  }

  return (
    <div>
      <h2>
        Cycles
        {awardTitle.length > 0 ? <span> for {awardTitle}</span> : <span></span>}
      </h2>
      {shouldMount && (
        <div
          className="error alert alert-danger"
          style={{
            transition: '.3s',
            opacity: stage === 'enter' ? 1 : 0,
          }}>
          {errorMessage}
        </div>
      )}
      {content}
    </div>
  )
}

CycleList.propTypes = {defaultAwardId: PropTypes.number}

const container = document.getElementById('CycleList') as HTMLElement
const root = createRoot(container)

root.render(<CycleList defaultAwardId={defaultAwardId} />)
