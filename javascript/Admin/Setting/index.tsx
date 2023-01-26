'use strict'
import React, {useState, useEffect} from 'react'
import {createRoot} from 'react-dom/client'
import Authentication from './Authentication'
import {InterfaceSettings} from './Interface'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {
  toggleAuthCall,
  toggleTrustedCall,
  toggleWarehouseCall,
} from './SettingXHR'

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

  const toggleWarehouse = () => {
    if (settings !== undefined) {
      settings.useWarehouse = !settings.useWarehouse
      setSettings({...settings})
      toggleWarehouseCall(settings.useWarehouse)
    }
  }

  const toggleAuth = (filename: string, toggle: boolean) => {
    if (settings !== undefined) {
      toggleAuthCall(filename, toggle, load)
    }
  }

  const toggleTrusted = () => {
    if (settings !== undefined) {
      settings.trustedDefault = !settings.trustedDefault
      setSettings({...settings})
      toggleTrustedCall(settings.trustedDefault)
    }
  }

  return (
    <div className="row">
      <div className="col-6 mx-auto">
        <h3>Authentication</h3>
        {settings && (
          <Authentication
            authAvailable={settings?.authAvailable}
            toggleAuth={toggleAuth}
          />
        )}
        <h3>Nomination search</h3>

        <table className="table">
          <tbody>
            <tr>
              <td>Use warehouse to autocomplete</td>
              <td>
                <a style={{cursor: 'pointer'}} onClick={toggleWarehouse}>
                  {settings?.useWarehouse ? iconOn : iconOff}
                </a>
              </td>
            </tr>
          </tbody>
        </table>

        <h3>Trusted</h3>
        <table className="table">
          <tbody>
            <tr>
              <td>
                Set new participants to{' '}
                <span className="text-success">Trusted</span> status
              </td>
              <td>
                <a style={{cursor: 'pointer'}} onClick={toggleTrusted}>
                  {settings?.trustedDefault ? iconOn : iconOff}
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  )
}

const container = document.getElementById('Setting') as HTMLElement
const root = createRoot(container)
root.render(<Setting />)
