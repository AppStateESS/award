export interface AuthAvailable {
  filename: string
  title: string
  enabled: boolean
}

export interface InterfaceSettings {
  authAvailable: AuthAvailable[]
}
