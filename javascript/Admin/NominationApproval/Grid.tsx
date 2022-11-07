'use strict'
import React from 'react'
import {NominationResource} from '../../ResourceTypes'
import {awardMonth} from '../../Share/Cycle'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'

interface NominationWithInfo extends NominationResource {
  awardTitle: string
  nominatedFirstName: string
  nominatedLastName: string
  nominatedEmail: string
  nominatorFirstName: string
  nominatorLastName: string
  nominatorEmail: string
  awardYear: number
  awardMonth: number
  term: string
}

const awardDate = (term: string, year: number, month: number) => {
  return term === 'yearly' ? year : awardMonth(month) + ', ' + year.toString()
}

type Props = {
  nominations: NominationWithInfo[]
}
const Grid = ({nominations}: Props) => {
  const approve = (nominationId: number) => {
    console.log('approval!')
  }
  const rows = nominations.map((value) => {
    return (
      <tr key={`nomination-${value.id}`}>
        <td>
          <button
            className="btn btn-success btn-sm"
            onClick={() => {
              approve(value.id)
            }}>
            Approve
          </button>
        </td>
        <td>{value.awardTitle}</td>
        <td>
          {value.term === 'yearly'
            ? value.awardYear
            : awardMonth(value.awardMonth)}
        </td>
        <td>
          {value.nominatorFirstName} {value.nominatorLastName}{' '}
          <a href={`mailto:${value.nominatorEmail}`}>
            <sup>
              <FontAwesomeIcon icon={['fas', 'envelope']} />
            </sup>
          </a>
        </td>
        <td>
          {value.nominatedFirstName} {value.nominatedLastName}{' '}
          <a href={`mailto:${value.nominatedEmail}`}>
            <sup>
              <FontAwesomeIcon icon={['fas', 'envelope']} />
            </sup>
          </a>
        </td>
      </tr>
    )
  })
  return (
    <table className="table table-striped">
      <tbody>
        <tr>
          <th></th>
          <th>Award</th>
          <th>Cycle</th>
          <th>Nominator</th>
          <th>Nominated</th>
        </tr>
        {rows}
      </tbody>
    </table>
  )
}

Grid.propTypes = {}
export default Grid
