'use strict'
import React, {useState, useEffect} from 'react'
import {createRoot} from 'react-dom/client'
import Authentication from './Authentication'
import {AuthAvailable, InterfaceSettings} from './Interface'
import axios from 'axios'

const Setting = () => {
  const [settings, setSettings] = useState<InterfaceSettings | null>()

  useEffect(() => {
    load()
  }, [])

  const toggleAuth = (filename: string, toggle: boolean) => {
    axios({
      method: 'post',
      url: 'award/Admin/Setting/authenticatorToggle',
      data: {filename, toggle: !toggle},
      timeout: 3000,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      },
    })
      .then((response) => {
        load()
      })
      .catch((error) => {
        console.log('Error:', error)
      })
  }

  const load = () => {
    const url = 'award/Admin/Setting/'
    axios
      .get(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
      .then((response) => {
        setSettings(response.data)
      })
  }

  return (
    <div className="row">
      <div className="col-6 mx-auto">
        <h3>Authentication</h3>
        {settings && (
          <Authentication
            authAvailable={settings.authAvailable}
            toggleAuth={toggleAuth}
          />
        )}
      </div>
    </div>
  )
}

const container = document.getElementById('Setting') as HTMLElement
const root = createRoot(container)
root.render(<Setting />)
