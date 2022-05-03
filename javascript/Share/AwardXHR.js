import axios from 'axios'
import 'regenerator-runtime'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

/**
 * Queries the server for a listing of awards. The default role
 * is 'User' which will only show public awards.
 * @param {object} options
 * @returns
 */
const getAwardList = async (options = {role: 'User'}) => {
  const method = 'get'
  const url = `./award/${options.role}/Award`
  const data = {}
  return await axios({
    method,
    url,
    data,
    timeout: 3000,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
    },
  })
}

const saveAward = async (award, role) => {
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
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
    },
  })
}

export {getAwardList, saveAward}
