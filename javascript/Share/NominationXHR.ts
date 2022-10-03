import axios from 'axios'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const nominateText = async (
  participantId: number,
  cycleId: number,
  reasonText: string
) => {
  const params = {participantId, cycleId, reasonText}
  return axios.post('./award/Participant/Nomination/text', , {
    headers: {
      params,
      'Content-Type': 'multipart/form-data',
    },
  })
}

const nominateDocument = async (
  participantId: number,
  cycleId: number,
  reasonFile: File
) => {
  const formData = new FormData()

  formData.append('document', reasonFile)
  formData.append('cycleId', cycleId.toString())
  formData.append('participantId', participantId.toString())

  return axios.post('./award/Participant/Nomination/upload', formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
}

export {nominateDocument, nominateText}