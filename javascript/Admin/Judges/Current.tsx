'use strict'
import React, {useState, useEffect} from 'react'
import PropTypes from 'prop-types'
import {ParticipantResource} from '../../ResourceTypes'

type Props = {judgeList: ParticipantResource[]}

const Current = ({judgeList}: Props) => {
  const content = judgeList.map((value) => {
    return (
      <div key={`judge-${value.id}`}>
        {value.firstName} {value.lastName}
      </div>
    )
  })
  return <div>{content}</div>
}

Current.propTypes = {judgeList: PropTypes.array}
export default Current
