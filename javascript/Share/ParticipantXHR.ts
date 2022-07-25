import axios from 'axios'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

/**
 * Sends a request to determine if an email address can be sent
 * for invitation.
 * @param {string} email
 * @returns Promise
 */
const canInviteGeneral = async (email: string) => {
  return axios.get('award/Admin/Participant/canInviteGeneral', {
    headers,
    params: {email},
  })
}

const resetPassword = async (
  participantId: number,
  password: string,
  hash: string
) => {
  const url = `award/User/Participant/${participantId}/resetPassword`

  return axios({
    method: 'put',
    url,
    data: {password, hash},
    timeout: 3000,
    headers,
  })
}

const saveNewParticipant = async (email: string, password: string) => {
  const data = {email, password}
  const url = 'award/User/Participant/create'
  return axios({
    method: 'post',
    url,
    data,
    timeout: 3000,
    headers,
  })
}

/**
 * Sends an invitation for an account creation.
 * Types:
 * 0 - general
 * 1 - judge
 * 2 - reference
 * 3 - nominated
 * @param {string} email
 * @returns Promise
 */
const sendInvitation = async (email: string, type: number = 0) => {
  return axios.post(
    'award/Admin/Invitation/',
    {
      email,
      type,
    },
    {headers}
  )
}

const signInPost = async (email: string, password: string) => {
  const data = {email, password}
  const url = 'award/User/Participant/signIn'
  return axios({
    method: 'post',
    url,
    data,
    timeout: 3000,
    headers,
  })
}

export {
  canInviteGeneral,
  resetPassword,
  saveNewParticipant,
  sendInvitation,
  signInPost,
}