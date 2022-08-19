/**
 * Returns a string based on invitation status number.
 * Based on the PHP defines:
 * AWARD_INVITATION_WAITING = 0
 * AWARD_INVITATION_CONFIRMED = 1
 * AWARD_INVITATION_REFUSED = 2
 * @param status
 * @returns string
 */
const confirmStatus = (status: number) => {
  switch (status) {
    case 0:
      return 'Waiting'
    case 1:
      return 'Confirmed'
    case 2:
      return 'Refused'
  }
}

const getInviteType = (inviteType: number) => {
  switch (inviteType) {
    case 1:
      return 'Judge'
    case 2:
      return 'Reference'
    case 3:
      return 'Nominated'
  }
}

export {confirmStatus, getInviteType}
