'use strict'
import React, {useState, useEffect} from 'react'
import {createRoot} from 'react-dom/client'
import {getList} from '../../Share/XHR'
import Loading from '../../Share/Loading'
import Listing from './Listing'

const AwardList = () => {
  const [loading, setLoading] = useState(true)
  const [awardList, setAwardList] = useState([])

  useEffect(() => {
    setLoading(true)
    const controller = new AbortController()
    const params = {
      url: 'award/Admin/Award',
      handleSuccess: (data) => {
        setLoading(false)
        setAwardList(data)
      },
      handleError: (error) => console.error(error),
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

const container = document.getElementById('AwardList')
const root = createRoot(container)
root.render(<AwardList />)
