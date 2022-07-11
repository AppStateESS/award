import axios from 'axios'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const saveNewParticipant = async (email, password) => {
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

const signInPost = async (email, password) => {
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
const sendInvitation = async (email, type = 0) => {
  return axios.post(
    'award/Admin/Invitation/',
    {
      email,
      type,
    },
    {headers}
  )
}

/**
 * Sends a request to determine if an email address can be sent
 * for invitation.
 * @param {string} email
 * @returns Promise
 */
const canInviteGeneral = async (email) => {
  return axios.get('award/Admin/Participant/canInviteGeneral', {
    headers,
    params: {email},
  })
}

export {saveNewParticipant, signInPost, canInviteGeneral, sendInvitation}
