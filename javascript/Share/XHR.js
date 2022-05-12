import axios from 'axios'
import 'regenerator-runtime'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const getItem = async (role, itemName, id) => {
  const controller = new AbortController()
  const url = `award/${role}/${itemName}/${id}`
  try {
    const response = await axios.get(url, {
      signal: controller.signal,
      headers,
    })
    return response
  } catch (error) {
    controller.abort()
    return false
  }
}

const getList = async ({url, handleSuccess, handleError, signal}) => {
  const params = {headers, signal}
  return await axios
    .get(url, params)
    .then((response) => {
      handleSuccess(response.data)
    })
    .catch((error) => {
      handleError(error)
    })
}

export {getItem, getList}
