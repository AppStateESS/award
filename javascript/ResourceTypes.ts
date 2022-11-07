export interface AwardBasic {
  id: number
  title: string
  cycleTerm: string
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

export interface InvitationResource {
  awardId: number
  awardTitle?: string
  id: number
  confirm: number
  created: string
  cycleId: number
  email: string
  invitedFirstName?: string
  invitedId: number
  invitedLastName?: string
  inviteType: number
  nominatedId: number
  nominatedFirstName?: string
  nominatedLastName?: string
  participantId: number
  senderId: number
  updated: string
}

export interface NominationResource {
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
  referenceReasonComplete: boolean | number
  referencesSelected: boolean | number
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
