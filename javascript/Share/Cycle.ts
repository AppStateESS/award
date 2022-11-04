import {AwardResource, CycleResource} from '../ResourceTypes'

const fullAwardTitle = (award: AwardResource, cycle: CycleResource) => {
  let awardTitle
  if (cycle.term === 'yearly') {
    awardTitle = `${cycle.awardYear} ${award.title}`
  } else {
    awardTitle = `${awardMonth(cycle.awardMonth)} ${award.title}`
  }

  if (awardTitle.match(/ award$/i) === null) {
    awardTitle += ' award'
  }
  return awardTitle
}

const awardMonth = (intMonth: number) => {
  return new Date(1, intMonth).toLocaleString('default', {month: 'long'})
}

export {awardMonth, fullAwardTitle}
