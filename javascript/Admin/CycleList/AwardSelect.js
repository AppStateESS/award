'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {Select} from '../../Share/Form/Form'

const AwardSelect = ({awardId, setAwardId, awardList}) => {
  const options = []
  awardList.forEach((award) => {
    options.push({value: award.id, label: award.title})
  })
  return (
    <Select
      name="awardId"
      label="Award"
      update={(value) => setAwardId(parseInt(value))}
      options={options}
      value={awardId}
    />
  )
}

AwardSelect.propTypes = {
  awardId: PropTypes.number,
  setAwardId: PropTypes.func,
  awardList: PropTypes.array,
}
export default AwardSelect
