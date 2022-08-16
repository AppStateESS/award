'use strict'
import React, {Fragment, useState, useEffect} from 'react'
import PropTypes from 'prop-types'
import {createRoot} from 'react-dom/client'

declare const templateValue: string

const Name = () => {
  return (
    <Fragment>
      <p>
        Sorry, but we didn&apos;t quite catch your name. Could please type them
        in below?
      </p>
    </Fragment>
  )
}

const container = document.getElementById('Name') as HTMLElement
const root = createRoot(container)
root.render(<Name />)
