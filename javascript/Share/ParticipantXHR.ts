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

const updateParticipant = async (
  participantId: number,
  firstName: string,
  lastName: string
) => {
  return axios({
    method: 'put',
    url: 'award/Admin/Participant/' + participantId,
    data: {firstName, lastName},
    timeout: 3000,
    headers,
  })
}

interface NewParticipant {
  email: string
  password: string
  firstName: string
  lastName: string
}

const saveNewParticipant = async (data: NewParticipant) => {
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

const search = async (search: string) => {
  const params = {search}
  const url = 'award/Participant/Participant/search'
  return axios.get(url, {params, headers})
}

export {
  canInviteGeneral,
  resetPassword,
  saveNewParticipant,
  search,
  sendInvitation,
  signInPost,
  updateParticipant,
}
