'use strict'
import React, {useState, useEffect, useRef} from 'react'
import PropTypes from 'prop-types'
import DateTimePicker from 'react-datetime-picker'
import {getList, saveResource} from '../../Share/XHR'
import 'react-calendar/dist/Calendar.css'
import 'react-datetime-picker/dist/DateTimePicker.css'
import 'react-clock/dist/Clock.css'
import './style.css'
import {Select} from '../../Share/Form/Form'
import {createRoot} from 'react-dom/client'
import {CycleResource, VoteTypes} from '../../ResourceTypes'
import Loading from '../../Share/Loading'

declare const defaultCycle: CycleResource
declare const awardTitle: string

const months = [
  {label: 'January', value: 1},
  {label: 'February', value: 2},
  {label: 'March', value: 3},
  {label: 'April', value: 4},
  {label: 'May', value: 5},
  {label: 'June', value: 6},
  {label: 'July', value: 7},
  {label: 'August', value: 8},
  {label: 'September', value: 9},
  {label: 'October', value: 10},
  {label: 'November', value: 11},
  {label: 'December', value: 12},
]

const currentYear = new Date().getFullYear()
const nextYear = currentYear + 1

const CycleForm = ({
  defaultCycle,
  awardTitle,
}: {
  defaultCycle: CycleResource
  awardTitle: string
}) => {
  const defaultMessage = {text: '', type: 'danger'}
  const [cycle, setCycle] = useState<CycleResource>(defaultCycle)
  const [message, setMessage] = useState(defaultMessage)
  const [dateError, setDateError] = useState(false)
  const [voteTypes, setVoteTypes] = useState<VoteTypes[]>([])
  const [voteTypeOptions, setVoteTypeOptions] = useState<VoteOptions[]>([])
  const [currentVoteType, setCurrentVoteType] = useState(0)
  const [years, setYears] = useState([
    {label: currentYear, value: currentYear, disabled: false},
    {label: nextYear, value: nextYear, disabled: false},
  ])
  const [yearUnavailable, setYearUnavailable] = useState(false)

  const steps = useRef(0)

  interface VoteOptions {
    label: string
    value: string | number
  }

  interface CurrentCycles {
    id: number
    awardYear: number
  }

  /**
   * Pull available vote types
   */
  useEffect(() => {
    const params = {
      url: 'award/Admin/Vote/types',
      handleSuccess: (data: Array<VoteTypes>) => {
        const vto: VoteOptions[] = []
        data.forEach((value, key) => {
          vto.push({label: value.title, value: key})
        })
        setVoteTypeOptions(vto)
        setVoteTypes(data)
        setCurrentVoteType(0)
        steps.current++
        //setSteps(steps + 1)
      },
    }
    getList(params)
  }, [])

  /**
   * If this is a new cycle, initialize the start and end date
   * based on today
   */
  useEffect(() => {
    if (cycle.id === 0) {
      const today = Math.floor(new Date().getTime() / 1000)
      const monthLater = today + 86400 * 30
      if (defaultCycle.startDate === 0) {
        update('startDate', today)
      }
      if (defaultCycle.endDate === 0) {
        update('endDate', monthLater)
      }
    }
    steps.current++
  }, [])

  /**
   * Get cycles within the next year.
   * Follow up prevents selection of matching years.
   */
  useEffect(() => {
    const config = {
      url: 'award/Admin/Cycle/current',
      params: {awardId: cycle.awardId},
      handleSuccess: (data: CurrentCycles[]) => {
        const yearMatch = [years[0].value, years[1].value]
        const found: number[] = []
        if (cycle.term === 'yearly') {
          data.forEach((value) => {
            const match = yearMatch.indexOf(value.awardYear)
            if (match !== -1) {
              found.push(match)
            }
          })

          if (found.length === 2) {
            setYearUnavailable(true)
          } else if (found.length > 0) {
            years.splice(found[0], 1)
            cycle.awardYear = years[0].value
            setCycle({...cycle})
          }
          setYears([...years])
          steps.current++
        }
      },
    }
    getList(config)
  }, [])

  /**
   * Make sure the end date is later than the start date.
   */
  useEffect(() => {
    setDateError(cycle.startDate > cycle.endDate)
  }, [cycle.startDate, cycle.endDate])

  const save = () => {
    const params = {
      resource: cycle,
      role: 'Admin',
      resourceName: 'Cycle',
      success: () =>
        (location.href = './award/Admin/Cycle/?awardId=' + cycle.awardId),
      failure: () => {
        setMessage({
          text: 'An error prevented saving the cycle',
          type: 'danger',
        })
      },
    }
    saveResource(params)
  }

  const updateVoteType = (key: number) => {
    update('voteType', voteTypes[key].className)
    setCurrentVoteType(key)
  }

  const update = <T extends keyof CycleResource>(
    param: T,
    value: CycleResource[T]
  ) => {
    cycle[param] = value
    setCycle({...cycle})
  }

  const updateStartDate = (e: Date) => {
    update('startDate', Math.floor(e.getTime() / 1000))
  }
  const updateEndDate = (e: Date) => {
    update('endDate', Math.floor(e.getTime() / 1000))
  }
  const startDate = new Date(cycle.startDate * 1000)
  const endDate = new Date(cycle.endDate * 1000)

  let voteInfo
  if (voteTypes.some((e) => e.description)) {
    voteInfo = (
      <p className="small bg-secondary p-1">
        <strong>Description:</strong> {voteTypes[currentVoteType].description}
      </p>
    )
  }

  const DateSelect = () => {
    if (cycle.id > 0) {
      return
    }

    if (cycle.term === 'monthly') {
      return (
        <div className="row">
          <div className="col-sm-4">
            <label>Which month and year is this award for?</label>
          </div>
          <div className="col-sm-4">
            <Select
              name="awardMonth"
              update={(e) => update('awardMonth', e)}
              value={cycle.awardMonth}
              options={months}
            />
          </div>
          <div className="col-sm-2">
            <Select
              name="awardYear"
              update={(e) => update('awardYear', e)}
              value={cycle.awardYear}
              options={years}
            />
          </div>
        </div>
      )
    } else if (cycle.term === 'yearly') {
      return (
        <div className="row">
          <div className="col-sm-4">
            <label>Which year is this award for?</label>
          </div>
          <div className="col-sm-4">
            <Select
              name="awardYear"
              update={(e) => update('awardYear', e)}
              value={cycle.awardYear}
              options={years}
            />
          </div>
        </div>
      )
    } else {
      return
    }
  }

  let term
  if (cycle.term === 'yearly') {
    term = 'Yearly'
  } else if (cycle.term === 'monthly') {
    term = 'Monthly'
  }

  if (steps.current < 3) {
    return (
      <div>
        <Loading things="cycle form elements" />
      </div>
    )
  }

  if (yearUnavailable) {
    return (
      <div>
        <h2>No yearly cycles available</h2>
        <p>Both this year and the next already have active cycles.</p>
        <p>
          You will need to{' '}
          <a href={`./award/Admin/Cycle/?awardId=${cycle.awardId}`}>
            return to the list
          </a>{' '}
          to either delete a cycle or edit a current one.
        </p>
      </div>
    )
  }
  return (
    <div>
      <h2>
        {cycle.id > 0 ? 'Update' : 'Create'} {term}{' '}
        <strong>{awardTitle}</strong> Cycle
      </h2>
      {message.text.length > 0 ? (
        <div className={`alert alert-${message.type}`}>{message.text}</div>
      ) : null}
      <div className="row mb-3">
        <div className="col-sm-4">When can participants start nominating?</div>
        <div className="col-sm-4">
          <DateTimePicker
            onChange={updateStartDate}
            value={startDate}
            clearIcon={null}
          />
          <button className="today" onClick={() => updateStartDate(new Date())}>
            Today
          </button>
        </div>
      </div>
      <div className="row mb-3">
        <div className="col-sm-4">What is the nomination deadline?</div>
        <div className="col-sm-4">
          <div>
            <DateTimePicker
              onChange={updateEndDate}
              value={endDate}
              clearIcon={null}
            />
            <button className="today" onClick={() => updateEndDate(new Date())}>
              Today
            </button>
          </div>
          {dateError && (
            <div className="badge badge-danger">The end date must be later</div>
          )}
        </div>
      </div>
      <div className="row">
        <div className="col-sm-4">
          <label>What voting method would you like to use?</label>
        </div>
        <div className="col-sm-6">
          <Select
            name="voteType"
            update={updateVoteType}
            value={currentVoteType}
            options={voteTypeOptions}
          />
          {voteInfo}
        </div>
      </div>
      {DateSelect()}

      <button className="btn btn-primary btn-block" onClick={save}>
        Save cycle
      </button>
    </div>
  )
}

CycleForm.propTypes = {
  defaultCycle: PropTypes.object,
  awardTitle: PropTypes.string,
}

const container = document.getElementById('CycleForm') as HTMLElement
const root = createRoot(container)
root.render(<CycleForm {...{defaultCycle, awardTitle}} />)
