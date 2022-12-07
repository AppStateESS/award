import axios from 'axios'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const getNominationReferences = (nominationId: number) => {
  const url = `./award/Admin/Reference/`
  return axios.get(url, {params: {nominationId}, headers})
}

const sendReferenceReasonReminder = (referenceId: number, role = 'Admin') => {
  if (role !== 'Admin' && role !== 'Participant') {
    role = 'Admin'
  }
  const url = `./award/${role}/Reference/${referenceId}/remind`

  return axios.get(url, {
    headers,
  })
}

const referenceText = async (referenceId: number, reasonText: string) => {
  const data = {referenceId, reasonText}
  return axios.put(`./award/Participant/Reference/${referenceId}/text`, data, {
    headers,
  })
}

export {getNominationReferences, referenceText, sendReferenceReasonReminder}
