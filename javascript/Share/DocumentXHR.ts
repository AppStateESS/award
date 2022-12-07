import axios from 'axios'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const nominateDocument = async (nominationId: number, reasonFile: File) => {
  const formData = new FormData()

  formData.append('document', reasonFile)
  formData.append('nominationId', nominationId.toString())

  return axios.post(`./award/Participant/Nomination/upload`, formData, {
    headers,
  })
}
const referenceDocument = async (referenceId: number, reasonFile: File) => {
  const formData = new FormData()

  formData.append('document', reasonFile)
  formData.append('referenceId', referenceId.toString())

  return axios.post(`./award/Participant/Reference/upload`, formData, {
    headers,
  })
}

const deleteParticipantDocument = async (documentId: number) => {
  return axios.delete(`./award/Participant/Document/${documentId}`, {headers})
}

export {deleteParticipantDocument, nominateDocument, referenceDocument}
