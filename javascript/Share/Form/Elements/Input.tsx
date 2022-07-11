'use strict'
import React from 'react'
import TextType from './TextType'

interface TextTypeProps {
  value: string
  update: (value: any) => void
  name?: string
  allowEmpty?: boolean
}

const Input = (props: TextTypeProps) => {
  return <TextType type="text" {...props} />
}
export default Input
