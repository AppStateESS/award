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

export interface VoteTypes {
  title: string
  description: string
  className: string
  allowParticipantVoting: boolean
}
