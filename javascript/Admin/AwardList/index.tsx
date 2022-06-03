'use strict'
import React, {useState, useEffect} from 'react'
import {createRoot} from 'react-dom/client'
import {getList} from '../../Share/XHR'
import Loading from '../../Share/Loading'
import Listing from './Listing'
import {AwardResource} from '../../ResourceTypes'

const AwardList = () => {
  const [loading, setLoading] = useState(true)
  const [awardList, setAwardList] = useState<AwardResource[]>([])

  useEffect(() => {
    setLoading(true)
    const controller = new AbortController()
    const params = {
      url: 'award/Admin/Award',
      handleSuccess: (data: AwardResource[]) => {
        setLoading(false)
        setAwardList(data)
      },
      handleError: (error: object) => console.error(error),
      signal: controller.signal,
    }
    getList(params)
    return () => controller.abort()
  }, [])

  let content
  if (loading) {
    content = <Loading things="awards" />
  } else if (awardList.length === 0) {
    content = (
      <div>
        No awards found. You need to{' '}
        <a href="./award/Admin/Award/create">create a new award</a>.
      </div>
    )
  } else {
    content = <Listing {...{awardList}} />
  }

  return <div>{content}</div>
}

AwardList.propTypes = {}

const container = document.getElementById('AwardList') as HTMLElement
const root = createRoot(container)
root.render(<AwardList />)
