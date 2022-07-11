export interface AwardBasic {
  id: number
  title: string
  cycleTerm: string
}

export interface AwardResource {
  id: number
  active: boolean
  approvalRequired: boolean
  creditNominator: boolean
  currentCycleId: number
  cycleTerm: string
  defaultVoteType: string
  description: string
  judgeMethod: number
  nominationReasonRequired: boolean
  participantId: number
  publicView: boolean
  referenceReasonRequired: boolean
  referencesRequired: number
  selfNominate: boolean
  tipNominated: boolean
  title: string
  winnerAmount: number
}

export interface CycleResource {
  id: number
  awardId: number
  awardMonth: number
  awardYear: number
  endDate: number
  startDate: number
  term: string
  voteAllowed: boolean
  voteType: string
}

export interface InvitationResource {
  id: number
  confirm: number
  cycleId: number
  email: string
  inviteType: number
  participantId: number
}

export interface ParticipantResource {
  id: number
  active: boolean
  authType: number
  banned: boolean
  email: string
  firstName: string
  hash: string
  lastName: string
  password?: string
  created: number
  updated: number
}

export interface VoteTypes {
  title: string
  description: string
  className: string
  allowParticipantVoting: boolean
}
