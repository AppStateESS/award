'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {getInviteType} from '../../Share/Invitation'
import {InvitationResource} from '../../ResourceTypes'

type Props = {
  currentInvite: InvitationResource | undefined
  finalRefusal: () => void
  close: () => void
}
const Refuse = ({currentInvite, finalRefusal, close}: Props) => {
  if (currentInvite === undefined) {
    return <span />
  }
  return (
    <div>
      <p>
        After clicking the refusal button below, we will inform the nominator
        that you are unable to serve as a{' '}
        <strong>{getInviteType(currentInvite.inviteType, false)}</strong> for
        the <strong>{currentInvite.awardTitle}</strong> award.
      </p>
      <p>You will not receive any further requests for this award cycle.</p>
      <p>
        If you want to accept this request, click the cancel button to return
        and then Accept.
      </p>
      <button className="btn btn-danger mr-2" onClick={finalRefusal}>
        Refuse {getInviteType(currentInvite.inviteType, false)} invitation
      </button>
      <button className="btn btn-secondary" onClick={close}>
        Cancel
      </button>
    </div>
  )
}

Refuse.propTypes = {}
export default Refuse
