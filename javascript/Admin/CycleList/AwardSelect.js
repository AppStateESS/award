'use strict'
import React, {useState, useEffect} from 'react'
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
      update={setAwardId}
      options={options}
      value={awardId}
    />
  )
}

AwardSelect.propTypes = {}
export default AwardSelect
