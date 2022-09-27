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
  id: number
  confirm: number
  cycleId: number
  email: string
  inviteType: number
  participantId: number
  firstName?: string
  lastName?: string
  awardTitle?: string
}

export interface NominationResource {
  id: number
  allowVote: boolean | number
  approved: boolean | number
  awardId: number
  bannerId: number
  completed: boolean | number
  cycleId: number
  email: string
  firstName: string
  lastName: string
  participantId: number
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

export interface VoteTypes {
  title: string
  description: string
  className: string
  allowParticipantVoting: boolean | number
}
