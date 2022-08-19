import axios from 'axios'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const acceptInvitation = async (invitationId: number) => {
  return axios({
    method: 'patch',
    url: `award/Participant/Invitation/${invitationId}/accept`,
    headers,
  })
}
const getCycleInvitations = async (cycleId: number) => {
  return axios.get('award/Admin/Invitation', {
    headers,
    params: {cycleId, includeInvited: true},
  })
}

const refuseInvitation = async (invitationId: number) => {
  return axios({
    method: 'patch',
    url: `award/Participant/Invitation/${invitationId}/refuse`,
    headers,
  })
}
const sendParticipantJudgeInvitation = async (
  invitedId: number,
  cycleId: number
) => {
  const url = 'award/Admin/Invitation/participantJudge'

  return axios({
    method: 'post',
    url,
    data: {invitedId, cycleId},
    timeout: 3000,
    headers,
  })
}

export {
  acceptInvitation,
  getCycleInvitations,
  refuseInvitation,
  sendParticipantJudgeInvitation,
}
