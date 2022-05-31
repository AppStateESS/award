'use strict'
import React, {useState} from 'react'
import PropTypes from 'prop-types'
import {saveAward} from '../../Share/AwardXHR'
import {
  Input,
  Textarea,
  Checkbox,
  Select,
  ButtonGroup,
} from '../../Share/Form/Form'
import './form.css'
import {createRoot} from 'react-dom/client'

const referencesRequiredOptions = [0, 1, 2, 3, 4, 5, 6, 6, 8, 9, 10]
const cycleTermOptions = [
  {value: 'yearly', label: 'Yearly award'},
  {value: 'monthly', label: 'Monthly award'},
  {value: 'randomly', label: 'No set period'},
]

const judgeMethod = [
  {value: 1, label: 'Judges'},
  {value: 0, label: 'All participants'},
]

/* global defaultAward */
const AwardForm = ({defaultAward}) => {
  const defaultMessage = {text: '', type: 'danger'}
  const [award, setAward] = useState(defaultAward)
  const [message, setMessage] = useState(defaultMessage)
  const update = (param, value) => {
    award[param] = value
    setAward({...award})
  }

  const save = () => {
    const newAward = award.id === 0
    let forwardUrl
    if (award.title.length === 0) {
      setMessage({text: 'Title is empty', type: 'danger'})
    } else {
      setMessage(defaultMessage)
      saveAward(award, 'Admin').then((response) => {
        if (response.data.success) {
          const awardId = response.data.id
          if (newAward) {
            forwardUrl = `./award/Admin/Award/${awardId}/newAward`
          } else {
            forwardUrl = `./award/Admin/Award/`
          }
          location.href = forwardUrl
        }
      })
    }
  }

  return (
    <div>
      <h2>{award.id > 0 ? 'Update' : 'Create'} Award</h2>
      <hr />
      {message.text.length > 0 ? (
        <div className={`alert alert-${message.type}`}>{message.text}</div>
      ) : null}
      <Input
        value={award.title}
        update={(value) => update('title', value)}
        name="title"
        columns={[4, 8]}
        allowEmpty={false}
      />
      <Textarea
        value={award.description}
        update={(value) => update('description', value)}
        columns={[4, 8]}
        name="description"
      />
      <div className="row mb-3">
        <div className="col-sm-4 d-flex align-items-center">
          Who determines the winner?
        </div>
        <div className="col-sm-8">
          <ButtonGroup
            buttonClass="outline-primary"
            options={judgeMethod}
            value={award.judgeMethod}
            update={(value) => update('judgeMethod', value)}
          />
        </div>
      </div>

      <div className="row">
        <div className="col-sm-4 d-flex align-items-center">
          <label>What time frame does this award represent?</label>
        </div>
        <div className="col-sm-4">
          <Select
            name="cycleTermOptions"
            options={cycleTermOptions}
            value={award.cycleTerm}
            update={(value) => update('cycleTerm', value)}
          />
        </div>
      </div>

      <div className="row">
        <div className="col-sm-9 mx-auto">
          <fieldset>
            <legend>Nominator</legend>
            <Checkbox
              value={award.creditNominator}
              label="List the winner's nominator"
              update={(value) => update('creditNominator', value)}
              columns={[7, 2]}
            />
            <Checkbox
              value={award.selfNominate}
              label="Nominators can nominate themselves for this award"
              columns={[7, 2]}
              update={(value) => update('selfNominate', value)}
            />
          </fieldset>
          <fieldset>
            <legend>References</legend>
            <div className="row">
              <div className="col-sm-7">
                <label>Number of references required</label>
              </div>
              <div className="col-sm-2">
                <Select
                  name="referencesRequiredOptions"
                  options={referencesRequiredOptions}
                  value={award.referencesRequired}
                  update={(value) => update('referencesRequired', value)}
                />
              </div>
            </div>

            <Checkbox
              value={award.referenceReasonRequired}
              label="References must include reasons for supporting the nomination"
              update={(value) => update('referenceReasonRequired', value)}
              columns={[7, 2]}
            />
          </fieldset>
          <fieldset>
            <legend>Nominations</legend>
            <Checkbox
              value={award.nominationReasonRequired}
              label="Nominator must supply a reason for the nomination"
              update={(value) => update('nominationReasonRequired', value)}
              columns={[7, 2]}
            />
            <Checkbox
              value={award.approvalRequired}
              label="Nominations must be approved before moving forward"
              update={(value) => update('approvalRequired', value)}
              columns={[7, 2]}
            />
            <Checkbox
              value={award.tipNominated}
              label="Notify a nominee of their nomination"
              columns={[7, 2]}
              update={(value) => update('tipNominated', value)}
            />
            <Checkbox
              value={award.publicView}
              columns={[7, 2]}
              label="Award winners are shown publicly"
              update={(value) => update('publicView', value)}
            />
          </fieldset>
        </div>
      </div>

      <button className="btn btn-primary btn-block" onClick={save}>
        Save award
      </button>
    </div>
  )
}

AwardForm.propTypes = {defaultAward: PropTypes.object}

const container = document.getElementById('AwardForm')
const root = createRoot(container)
root.render(<AwardForm {...{defaultAward}} />)
