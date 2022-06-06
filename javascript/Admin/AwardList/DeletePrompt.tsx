'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {AwardResource} from '../../ResourceTypes'

const DeletePrompt = ({award}: {award: AwardResource | undefined}) => {
  if (award === undefined) {
    return <span />
  }
  return (
    <div>
      <p>You are about to delete an award. A deleted award:</p>
      <ul>
        <li>will not appear in the administrative list;</li>
        <li>will no longer have any active cycles;</li>
        <li>will not show information; and</li>
        <li>will not show previous winners</li>
      </ul>
      <p>
        Deleting an award will <strong>NOT</strong> remove it from a
        participant&apos;s winner list. The participant will need to hide it
        themself.
      </p>
    </div>
  )
}

DeletePrompt.propTypes = {award: PropTypes.object}
export default DeletePrompt
