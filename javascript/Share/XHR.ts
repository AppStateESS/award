import axios, {AxiosError} from 'axios'
import 'regenerator-runtime'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const deleteItem = async (role: string, itemName: string, id: number) => {
  const controller = new AbortController()
  const url = `award/${role}/${itemName}/${id}`
  return await axios.delete(url, {
    signal: controller.signal,
    headers,
  })
}

const getItem = async (role: string, itemName: string, id: number) => {
  const controller = new AbortController()
  const url = `award/${role}/${itemName}/${id}`
  return await axios.get(url, {
    signal: controller.signal,
    headers,
  })
}

interface getListParams {
  url: string
  handleSuccess: (data: Array<any>) => void
  handleError?: (error: AxiosError) => void
  signal: AbortSignal
}

const getList = async ({
  url,
  handleSuccess,
  handleError,
  signal,
}: getListParams) => {
  const params = {headers, signal}
  if (handleError === undefined) {
    handleError = (e: AxiosError) => console.error(e)
  }
  return await axios
    .get(url, params)
    .then((response) => {
      handleSuccess(response.data)
    })
    .catch((error: AxiosError) => {
      handleError?.(error)
    })
}

const saveResource = async ({
  resource,
  role,
  resourceName,
  success,
  failure,
}: {
  resource: Record<string, string | number | boolean>
  role: string
  resourceName: string
  success: (data: any) => void
  failure: (error: Error | AxiosError) => void
}) => {
  if (failure === undefined) {
    failure = (error: Error | AxiosError) => console.error(error)
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
