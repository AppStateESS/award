/**
 * Returns a string based on invitation status number.
 * Based on the PHP defines:
 * AWARD_INVITATION_WAITING = 0
 * AWARD_INVITATION_CONFIRMED = 1
 * AWARD_INVITATION_REFUSED = 2
 * @param status
 * @returns string
 */
export const confirmStatus = (status: number) => {
  switch (status) {
    case 0:
      return 'Waiting'
    case 1:
      return 'Confirmed'
    case 2:
      return 'Refused'
  }
}
