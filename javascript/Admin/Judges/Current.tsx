'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {ParticipantResource} from '../../ResourceTypes'

type Props = {judgeList: ParticipantResource[]}

const Current = ({judgeList}: Props) => {
  const content = judgeList.map((value) => {
    return (
      <div key={`judge-${value.id}`}>
        <a href={`mailto:${value.email}`}>
          {value.firstName} {value.lastName}
        </a>
      </div>
    )
  })
  return <div>{content}</div>
}

Current.propTypes = {judgeList: PropTypes.array}
export default Current
