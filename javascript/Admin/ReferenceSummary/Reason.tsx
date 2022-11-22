'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {ReferenceResource} from '../../ResourceTypes'

type Props = {reference: ReferenceResource; showReasonText: () => void}
const Reason = ({reference, showReasonText}: Props) => {
  let textLink
  let documentLink
  let bar
  let reasonSupplied = false

  if (reference.reasonText.length > 0) {
    reasonSupplied = true
    textLink = <a onClick={showReasonText}>Text</a>
  }
  if (reference.reasonDocument !== 0) {
    reasonSupplied = true
    documentLink = <a href="">Document</a>
  }
  if (textLink && documentLink) {
    bar = ' | '
  }
  if (reasonSupplied) {
    return (
      <div>
        {textLink} {bar} {documentLink}
      </div>
    )
  } else {
    return <div className="badge badge-danger">No endorsement</div>
  }
}

Reason.propTypes = {reference: PropTypes.object, showReasonText: PropTypes.func}
export default Reason
