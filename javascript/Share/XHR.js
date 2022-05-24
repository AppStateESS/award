import axios from 'axios'
import 'regenerator-runtime'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const deleteItem = async (role, itemName, id) => {
  const controller = new AbortController()
  const url = `award/${role}/${itemName}/${id}`
  return await axios.delete(url, {
    signal: controller.signal,
    headers,
  })
}

const getItem = async (role, itemName, id) => {
  const controller = new AbortController()
  const url = `award/${role}/${itemName}/${id}`
  return await axios.get(url, {
    signal: controller.signal,
    headers,
  })
}

const getList = async ({url, handleSuccess, handleError, signal}) => {
  const params = {headers, signal}
  if (handleError === undefined) {
    handleError = (e) => console.error(e)
  }
  return await axios
    .get(url, params)
    .then((response) => {
      handleSuccess(response.data)
    })
    .catch((error) => {
      handleError(error)
    })
}

const saveResource = async ({
  resource,
  role,
  resourceName,
  success,
  failure,
}) => {
  if (failure === undefined) {
    failure = (e) => console.error(e)
  }
  const method = resource.id > 0 ? 'put' : 'post'
  let url = `./award/${role}/${resourceName}`
  if (resource.id > 0) {
    url += '/' + resource.id
  }
  return await axios({
    method,
    url,
    data: resource,
    timeout: 3000,
    headers,
  })
    .then((response) => {
      success(response.data)
    })
    .catch((error) => {
      failure(error)
    })
}

export {getItem, getList, saveResource, deleteItem}
