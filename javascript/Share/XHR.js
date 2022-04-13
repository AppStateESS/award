import axios from 'axios'
import 'regenerator-runtime'
const headers = {'X-Requested-With': 'XMLHttpRequest'}

const getItem = async (role, itemName, id) => {
  const url = `award/${role}/${itemName}/${id}`
  try {
    const response = await axios.get(url, {
      headers,
    })
    return response
  } catch (error) {
    return false
  }
}

const checkEmail = async (email) => {
  const url = 'award/User/Participant/exists'
  return await axios.get(url, {headers})
}

export {checkEmail, getItem}
