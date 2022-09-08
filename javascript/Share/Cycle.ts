import {AwardResource, CycleResource} from '../ResourceTypes'

const fullAwardTitle = (award: AwardResource, cycle: CycleResource) => {
  let awardTitle
  if (cycle.term === 'yearly') {
    awardTitle = `${cycle.awardYear} ${award.title}`
  } else {
    awardTitle = `${cycle.awardMonth} ${award.title}`
  }

  if (awardTitle.match(/ award$/i) === null) {
    awardTitle += ' award'
  }
  return awardTitle
}

export {fullAwardTitle}
