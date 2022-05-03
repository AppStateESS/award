'use strict'
import React, {useState, useEffect} from 'react'
import PropTypes from 'prop-types'
import {saveAward} from '../../Share/AwardXHR'
import {
  Input,
  Textarea,
  Checkbox,
  Select,
  ButtonGroup,
} from '../../Share/Form/Form'
import {createRoot} from 'react-dom/client'

const referencesRequiredOptions = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]

const judgeMethod = [
  {value: 1, label: 'Judges'},
  {value: 0, label: 'All participants'},
]

/* global defaultAward */
const AwardForm = () => {
  const defaultMessage = {text: '', type: 'danger'}
  const [award, setAward] = useState(defaultAward)
  const [message, setMessage] = useState(defaultMessage)
  const update = (param, value) => {
    award[param] = value
    setAward({...award})
  }

  const save = () => {
    if (award.title.length === 0) {
      setMessage({text: 'Title is empty', type: 'danger'})
    } else {
      setMessage(defaultMessage)
      saveAward(award, 'Admin').then((response) => {
        console.log(response.data)
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
        allowEmpty={false}
      />
      <Textarea
        value={award.description}
        update={(value) => update('description', value)}
        name="description"
      />
      <div className="row mb-3">
        <div className="col-sm-6">Who determines the winner?</div>
        <div className="col-sm-6">
          <ButtonGroup
            buttonClass="outline-primary"
            options={judgeMethod}
            value={award.judgeMethod}
            update={(value) => update('judgeMethod', value)}
          />
        </div>
      </div>

      <Select
        name="referencesRequiredOptions"
        label="Number of references required"
        options={referencesRequiredOptions}
        columns={[6, 3]}
        value={award.referencesRequired}
        update={(value) => update('referencesRequired', value)}
      />

      <div className="row">
        <div className="col-md-6">
          <Checkbox
            value={award.creditNominator}
            label="List the winner's nominator"
            update={(value) => update('creditNominator', value)}
            columns={[8, 2]}
          />
          <Checkbox
            value={award.nominationReasonRequired}
            label="Nominator must supply a reason for the nomination"
            update={(value) => update('nominationReasonRequired', value)}
            columns={[8, 2]}
          />
          <Checkbox
            value={award.referenceReasonRequired}
            label="References must include reasons for supporting the nomination"
            update={(value) => update('referenceReasonRequired', value)}
            columns={[8, 2]}
          />
        </div>
        <div className="col-md-6">
          <Checkbox
            value={award.publicView}
            columns={[8, 2]}
            label="Award winners are shown publicly"
            update={(value) => update('publicView', value)}
          />
          <Checkbox
            value={award.selfNominate}
            label="Nominators can nominate themselves for this award"
            columns={[8, 2]}
            update={(value) => update('selfNominate', value)}
          />
          <Checkbox
            value={award.tipNominated}
            label="Notify a nominee of their nomination"
            columns={[8, 2]}
            update={(value) => update('tipNominated', value)}
          />
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
