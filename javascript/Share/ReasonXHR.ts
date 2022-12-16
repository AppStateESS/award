import axios from 'axios'
import {ReasonResource} from '../ResourceTypes'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

export const postReasonText = async (reason: ReasonResource) => {
  const data = {...reason}

  return axios.post(`./award/Participant/Reason/`, data, {
    headers,
  })
}

export const putReasonText = async (reasonId: number, reasonText: string) => {
  const data = {reasonId, reasonText}
  const url = `./award/Participant/Reason/${reasonId}`

  return axios.put(url, data, {
    headers,
  })
}

export const removeReasonDocument = async (reasonId: number) => {
  return axios.delete(`./award/Participant/Reason/${reasonId}/removeDocument`, {
    headers,
  })
}
