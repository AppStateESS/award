'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {ParticipantResource} from '../../ResourceTypes'

type Props = {referenceList: ParticipantResource[]}

const Current = ({referenceList}: Props) => {
  const content = referenceList.map((value) => {
    return (
      <div key={`reference-${value.id}`}>
        <a href={`mailto:${value.email}`}>
          {value.firstName} {value.lastName}
        </a>
      </div>
    )
  })
  return <div>{content}</div>
}

Current.propTypes = {referenceList: PropTypes.array}
export default Current
