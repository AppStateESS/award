import axios from 'axios'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const getNominationReferences = (nominationId: number) => {
  const url = `./award/Admin/Reference/`
  return axios.get(url, {params: {nominationId}, headers})
}

const sendReferenceReasonReminder = (referenceId: number) => {
  const url = `./award/Participant/Reference/${referenceId}/remind`

  return axios.get(url, {
    headers,
  })
}

export {getNominationReferences, sendReferenceReasonReminder}
