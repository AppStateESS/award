export interface AuthAvailable {
  filename: string
  title: string
  enabled: boolean
}

export interface InterfaceSettings {
  authAvailable: AuthAvailable[]
  siteContactName: string
  siteContactEmail: string
  useWarehouse: boolean
  trustedDefault: boolean
}
