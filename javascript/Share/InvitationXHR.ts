import axios from 'axios'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

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

export {sendParticipantJudgeInvitation}
