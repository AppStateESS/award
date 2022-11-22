export interface AwardBasic {
  id: number
  title: string
  cycleTerm: string
}

interface Nominated {
  nominatedEmail?: string
  nominatedFirstName?: string
  nominatedLastName?: string
}
interface Nominator {
  nominatorEmail?: string
  nominatorFirstName?: string
  nominatorLastName?: string
}

export interface AwardResource {
  id: number
  active: boolean | number
  approvalRequired: boolean | number
  creditNominator: boolean | number
  currentCycleId: number
  cycleTerm: string
  defaultVoteType: string
  description: string
  judgeMethod: number
  nominationReasonRequired: boolean | number
  participantId: number
  publicView: boolean | number
  referenceReasonRequired: boolean | number
  referencesRequired: number
  selfNominate: boolean | number
  tipNominated: boolean | number
  title: string
  winnerAmount: number
}

export interface CycleResource {
  id: number
  awardId: number
  awardMonth: number
  awardYear: number
  endDate: number
  judgeMethod?: number
  startDate: number
  term: string
  voteAllowed: boolean | number
  voteType: string
}

export interface InvitationResource extends Nominated {
  awardId: number
  awardTitle?: string
  id: number
  confirm: number
  created: string
  cycleId: number
  email: string
  invitedFirstName?: string
  invitedEmail?: string
  invitedId: number
  invitedLastName?: string
  inviteType: number
  lastReminder: string
  participantId: number
  senderId: number
  updated: string
}

export interface NominationResource extends Nominator, Nominated {
  id: number
  allowVote: boolean | number
  approved: boolean | number
  awardId: number
  completed: boolean | number
  cycleId: number
  participantId: number
  reasonText: string
  reasonComplete: boolean | number
  reasonDocument: number
  referencesComplete: boolean | number
  referencesSelected: boolean | number
}

export interface ParticipantSummary {
  participantFirstName?: string
  participantLastName?: string
  participantEmail?: string
}

export interface ParticipantResource {
  id: number
  active: boolean | number
  authType: number
  banned: boolean | number
  email: string
  firstName: string
  hash: string
  lastName: string
  password?: string
  created: number
  updated: number
  trusted: boolean | number
}

export interface ReferenceResource
  extends Nominated,
    Nominator,
    ParticipantSummary {
  id: number
  awardId: number
  cycleId: number
  lastReminder: string
  reasonDocument: number
  reasonText: string
  nominationId: number
  participantId: number
  email?: string
  firstName?: string
  lastName?: string
}

export interface ReferenceInvitation extends InvitationResource {
  firstName: string
  lastName: string
}

export interface VoteTypes {
  title: string
  description: string
  className: string
  allowParticipantVoting: boolean | number
}

/**
 * Mirror of config/system.php with 'AWARD_' removed.
 */
export const AwardDefines = {
  INVITATION_WAITING: 0,
  INVITATION_CONFIRMED: 1,
  INVITATION_REFUSED: 2,
  INVITATION_NO_CONTACT: 3,
  INVITE_TYPE_NEW: 0,
  INVITE_TYPE_JUDGE: 1,
  INVITE_TYPE_REFERENCE: 2,
  INVITE_TYPE_NOMINATED: 3,
  HASH_DEFAULT_TIMER_HOURS: 2,
  JUDGE_REMINDER_GRACE: 2,
  JUDGE_INVITE_REMINDER_GRACE: 2,
  NOMINATED_INVITE_REMINDER_GRACE: 2,
  REFERENCE_REMINDER_GRACE: 2,
  REFERENCE_INVITE_REMINDER_GRACE: 2,
}
