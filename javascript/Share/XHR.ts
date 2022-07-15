import axios, {AxiosError, AxiosResponse} from 'axios'
import 'regenerator-runtime'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const deleteItem = async (role: string, itemName: string, id: number) => {
  const controller = new AbortController()
  const url = `award/${role}/${itemName}/${id}`
  return axios.delete(url, {
    signal: controller.signal,
    headers,
  })
}

const getItem = async (role: string, itemName: string, id: number) => {
  const controller = new AbortController()
  const url = `award/${role}/${itemName}/${id}`
  return axios.get(url, {
    signal: controller.signal,
    headers,
  })
}

interface GetListParams {
  url: string
  handleSuccess: (data: Array<any>) => void
  handleError?: (error: AxiosError) => void
  params?: {[key: string]: string | number | boolean}
  signal?: AbortSignal
}

const getList = async ({
  url,
  handleSuccess,
  handleError,
  signal,
  params,
}: GetListParams) => {
  const config = {headers, params, signal}
  return axios
    .get<Record<string, unknown>[]>(url, config)
    .then((response: AxiosResponse) => {
      handleSuccess(response.data)
    })
    .catch((error: AxiosError) => {
      if (handleError === undefined) {
        throw error
      } else {
        handleError?.(error)
      }
    })
}

const saveResource = async ({
  resource,
  role,
  resourceName,
  success,
  failure,
}: {
  resource: any
  role: string
  resourceName: string
  success: (data: any) => void
  failure: (error: Error | AxiosError) => void
}) => {
  const method = resource.id > 0 ? 'put' : 'post'
  let url = `./award/${role}/${resourceName}`
  if (resource.id > 0) {
    url += '/' + resource.id
  }
  return axios({
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
      if (failure === undefined) {
        throw failure
      } else {
        failure(error)
      }
    })
}

export {getItem, getList, saveResource, deleteItem}
