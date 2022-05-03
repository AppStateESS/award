'use strict'
import React, {Fragment, useState, useEffect} from 'react'
import PropTypes from 'prop-types'
import {createRoot} from 'react-dom/client'

const AwardList = () => {
  return <div>AwardList</div>
}

AwardList.propTypes = {}

const container = document.getElementById('AwardList')
const root = createRoot(container)
root.render(<AwardList />)
