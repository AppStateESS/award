import axios from 'axios'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const nominateText = async (nominationId: number, reasonText: string) => {
  const data = {nominationId, reasonText}
  return axios.put(
    `./award/Participant/Nomination/${nominationId}/text`,
    data,
    {
      headers,
    }
  )
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
    headers,
  })
}

export {nominateDocument, nominateText}
