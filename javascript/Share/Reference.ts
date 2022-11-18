import {ReferenceResource} from '../ResourceTypes'

const reasonCompleted = (reference: ReferenceResource) => {
  return (
    (reference.reasonDocument !== null && reference.reasonDocument > 0) ||
    (reference.reasonText !== null && reference.reasonText.length > 0)
  )
}

export {reasonCompleted}
