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

const nominationApprovalList = async () => {
  const url = './award/Admin/Nomination/needsApproval'

  return axios.get(url, {
    headers: {'X-Requested-With': 'XMLHttpRequest'},
  })
}

const postNomination = async (participantId: number, cycleId: number) => {
  const url = 'award/Participant/Nomination'
  const data = {participantId, cycleId}

  return axios({
    method: 'post',
    url,
    data,
    timeout: 3000,
    headers: {'X-Requested-With': 'XMLHttpRequest'},
  })
}

export {nominationApprovalList, nominateDocument, nominateText, postNomination}
