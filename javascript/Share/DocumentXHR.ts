import axios, {AxiosRequestConfig} from 'axios'
import {ReasonResource} from '../ResourceTypes'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

export const uploadDocument = async (
  reason: ReasonResource,
  reasonFile: File
) => {
  const formData = new FormData()
  const url = `./award/Participant/Reason/upload`

  if (reason.id > 0) {
    formData.append('reasonId', reason.id.toString())
  } else {
    formData.append('referenceId', reason.referenceId.toString())
    formData.append('nominationId', reason.nominationId.toString())
  }

  formData.append('document', reasonFile)
  formData.append('reasonType', reason.reasonType.toString())

  const config: AxiosRequestConfig = {
    method: 'post',
    url,
    data: formData,
    timeout: 3000,
    headers,
  }

  return axios(config)
}

export const deleteParticipantDocument = async (documentId: number) => {
  return axios.delete(`./award/Participant/Document/${documentId}`, {headers})
}
