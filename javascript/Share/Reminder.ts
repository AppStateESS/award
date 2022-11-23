import {AwardDefines} from '../ResourceTypes'

/**
 * Returns TRUE if the grace period after the last reminder
 * has passed.
 * @param lastReminder
 * @param inviteType
 * @returns boolean
 */
const invitationReminderAllowed = (
  lastReminder: string,
  inviteType: number
) => {
  const todayDate = new Date()
  const todayUnix = todayDate.getTime()
  const reminderUnix = Date.parse(lastReminder)
  let grace = 0
  switch (inviteType) {
    case AwardDefines.INVITE_TYPE_JUDGE:
      grace = AwardDefines.JUDGE_INVITE_REMINDER_GRACE
      break
    case AwardDefines.INVITE_TYPE_REFERENCE:
      grace = AwardDefines.REFERENCE_INVITE_REMINDER_GRACE
      break
    case AwardDefines.INVITE_TYPE_NOMINATED:
      grace = AwardDefines.NOMINATED_INVITE_REMINDER_GRACE
      break
  }

  return todayUnix > reminderUnix + 86400000 * grace
}

const referenceReminderAllowed = (lastReminder: string) => {
  const todayDate = new Date()
  const todayUnix = todayDate.getTime()
  const reminderUnix = Date.parse(lastReminder)

  return (
    todayUnix > reminderUnix + 86400000 * AwardDefines.REFERENCE_REMINDER_GRACE
  )
}

export {invitationReminderAllowed, referenceReminderAllowed}
