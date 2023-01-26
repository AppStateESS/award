import axios from 'axios'

export const toggleAuthCall = (
  filename: string,
  toggle: boolean,
  callback: () => void
) => {
  axios({
    method: 'post',
    url: 'award/Admin/Setting/authenticatorToggle',
    data: {filename, toggle: !toggle},
    timeout: 3000,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
    },
  })
    .then(() => {
      callback()
    })
    .catch((error) => {
      console.log('Error:', error)
    })
}

export const toggleWarehouseCall = (toggle: boolean) => {
  const url = 'award/Admin/Setting/warehouseToggle'
  const data = {useWarehouse: toggle}

  return axios({
    method: 'post',
    url,
    data,
    timeout: 3000,
    headers: {'X-Requested-With': 'XMLHttpRequest'},
  })
}

export const toggleTrustedCall = (toggle: boolean) => {
  const url = 'award/Admin/Setting/trustedToggle'
  const data = {trusted: toggle}

  return axios({
    method: 'post',
    url,
    data,
    timeout: 3000,
    headers: {'X-Requested-With': 'XMLHttpRequest'},
  })
}
