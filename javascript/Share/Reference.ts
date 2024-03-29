import {ReferenceResource} from '../ResourceTypes'

const reasonCompleted = (reference: ReferenceResource) => {
  return reference.reasonDocument > 0 || reference.reasonText?.length > 0
}

export {reasonCompleted}
