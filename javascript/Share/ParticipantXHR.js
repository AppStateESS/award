import axios from 'axios'
import 'regenerator-runtime'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const saveNewParticipant = async (email, password) => {
  const data = {email, password}
  const url = 'award/User/Participant/create'
  return await axios({
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
  return await axios({
    method: 'post',
    url,
    data,
    timeout: 3000,
    headers,
  })
}

export {saveNewParticipant, signInPost}
