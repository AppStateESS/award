'use strict'
import React, {useState, useEffect} from 'react'
import PropTypes from 'prop-types'
import {createRoot} from 'react-dom/client'
import {getItem} from '../../Share/XHR'
import {CycleResource} from '../../ResourceTypes'

declare const cycle: CycleResource

const Nominate = () => {
  return <div>{cycle.term}</div>
}

const container = document.getElementById('Nominate') as HTMLElement
const root = createRoot(container)
root.render(<Nominate />)
