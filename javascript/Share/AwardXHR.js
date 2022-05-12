import axios from 'axios'

import 'regenerator-runtime'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

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
    headers,
  })
}

export {saveAward}
