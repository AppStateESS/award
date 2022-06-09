'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {CycleResource} from '../../ResourceTypes'

const DeletePrompt = ({cycle}: {cycle: CycleResource | undefined}) => {
  if (cycle === undefined) {
    return <span />
  }
  return (
    <div>
      <p>You are about to delete an award cycle. A deleted cycle:</p>
      <ul>
        <li>will not appear in the administrative list;</li>
        <li>will not show information; and</li>
        <li>will not show previous winners</li>
      </ul>
      <p>
        Deleting a cycle will <strong>NOT</strong> remove it from a
        participant&apos;s winner list. The participant will need to hide it
        themself.
      </p>
    </div>
  )
}

DeletePrompt.propTypes = {award: PropTypes.object}
export default DeletePrompt
