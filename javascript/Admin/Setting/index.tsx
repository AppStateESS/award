'use strict'
import React, {useState, useEffect} from 'react'
import {createRoot} from 'react-dom/client'
import Authentication from './Authentication'
import {InterfaceSettings} from './Interface'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'

const iconOff = (
  <FontAwesomeIcon
    className="text-danger"
    icon={['fas', 'toggle-off']}
    size="2x"
  />
)

const iconOn = (
  <FontAwesomeIcon
    className="text-success"
    icon={['fas', 'toggle-on']}
    size="2x"
  />
)
import axios from 'axios'

const Setting = () => {
  const [settings, setSettings] = useState<InterfaceSettings>()

  useEffect(() => {
    load()
  }, [])

  const load = () => {
    const url = 'award/Admin/Setting/'
    axios
      .get(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
      .then((response) => {
        setSettings(response.data)
      })
  }

  const toggleWarehouse = (toggle: boolean) => {
    if (settings !== undefined) {
      settings.useWarehouse = !toggle
      setSettings({...settings})
      const url = 'award/Admin/Setting/warehouseToggle'
      const data = {useWarehouse: settings.useWarehouse}

      return axios({
        method: 'post',
        url,
        data,
        timeout: 3000,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
      })
    }
  }

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
        <h3>Nomination search</h3>
        {settings && (
          <table className="table">
            <tbody>
              <tr>
                <td>Use warehouse to autocomplete</td>
                <td>
                  <a
                    style={{cursor: 'pointer'}}
                    onClick={() => {
                      toggleWarehouse(settings.useWarehouse)
                    }}>
                    {settings.useWarehouse ? iconOn : iconOff}
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        )}
      </div>
    </div>
  )
}

const container = document.getElementById('Setting') as HTMLElement
const root = createRoot(container)
root.render(<Setting />)
