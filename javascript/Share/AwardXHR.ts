import axios from 'axios'
import {AwardResource} from '../ResourceTypes'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const activate = async (awardId: number, active: boolean) => {
  const method = 'patch'
  const url = `./award/Admin/Award/${awardId}/activate`
  const data = {active}
  return axios({
    method,
    url,
    data,
    headers,
  })
}

/**
 * Expects an array response containing objects of just the id and title.
 * @returns Promise
 */
const getSelectList = async () => {
  return axios.get('award/Admin/Award/?asSelect=true', {headers})
}

const saveAward = async (award: AwardResource, role: string) => {
  const method = award.id > 0 ? 'put' : 'post'
  let url = `./award/${role}/Award`
  if (award.id > 0) {
    url += '/' + award.id
  }
  return axios({
    method,
    url,
    data: award,
    timeout: 3000,
    headers,
  })
}

const getHasCycles = async (awardId: number) => {
  return axios.get(`award/Admin/Award/${awardId}/hasCycles`, {headers})
}

export {activate, getHasCycles, saveAward, getSelectList}
