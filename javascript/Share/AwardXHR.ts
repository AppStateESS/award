import axios from 'axios'
import {AwardResource} from '../ResourceTypes'
import 'regenerator-runtime'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const saveAward = async (award: AwardResource, role: string) => {
  const method = award.id > 0 ? 'put' : 'post'
  let url = `./award/${role}/Award`
  if (award.id > 0) {
    url += '/' + award.id
  }
  return await axios({
    method,
    url,
    data: award,
    timeout: 3000,
    headers,
  })
}

const getHasCycles = async (awardId: number) => {
  return await axios.get(`award/Admin/Award/${awardId}/hasCycles`, {headers})
}

export {saveAward, getHasCycles}
