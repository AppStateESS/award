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

export {nominateDocument}
