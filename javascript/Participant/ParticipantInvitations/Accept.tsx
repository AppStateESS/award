'use strict'
import React from 'react'
import PropTypes from 'prop-types'
import {getInviteType} from '../../Share/Invitation'
import {InvitationResource} from '../../ResourceTypes'

type Props = {
  currentInvite: InvitationResource | undefined
  finalAccept: () => void
  close: () => void
}
const Accept = ({currentInvite, finalAccept, close}: Props) => {
  if (currentInvite === undefined) {
    return <span />
  }

  let responsibilities

  if (currentInvite.inviteType === 1) {
    responsibilities = (
      <>
        <p>
          You will be required to review all nominations, references (if
          applicable), and possibly consult with other judges.
        </p>
        <p>Your vote will determine this award&apos;s winner (or winners).</p>
      </>
    )
  } else if (currentInvite.inviteType === 2) {
    responsibilities = (
      <p>
        You will be required to verify a nominee&apos;s qualifications to
        receive this award.
      </p>
    )
  }

  return (
    <div>
      <p>
        By clicking the acceptance button below, you are agreeing to be a{' '}
        <strong>{getInviteType(currentInvite.inviteType, false)}</strong> for
        the <strong>{currentInvite.awardTitle}</strong> award.
      </p>
      {responsibilities}
      <p>
        If you are able to assume this responsibility, please click{' '}
        <span className="text-success">Accept</span> below. If you cannot,
        Cancel below and then chose to refuse instead.
      </p>
      <hr />
      <button className="btn btn-success mr-2" onClick={finalAccept}>
        Accept {getInviteType(currentInvite.inviteType, false)} position
      </button>
      <button className="btn btn-secondary" onClick={close}>
        Cancel
      </button>
    </div>
  )
}

Accept.propTypes = {
  currentInvite: PropTypes.object,
  finalAccept: PropTypes.func,
  close: PropTypes.func,
}
export default Accept
