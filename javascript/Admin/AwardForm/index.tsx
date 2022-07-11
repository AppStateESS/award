'use strict'
import React, {useState, useEffect} from 'react'
import PropTypes from 'prop-types'
import {saveAward, getHasCycles} from '../../Share/AwardXHR'
import {
  Input,
  Textarea,
  Checkbox,
  Select,
  ButtonGroup,
  Labeled,
} from '../../Share/Form/Form'
import {AwardResource} from '../../ResourceTypes'
import './form.css'
import {createRoot} from 'react-dom/client'

const referencesRequiredOptions = [0, 1, 2, 3, 4, 5, 6, 6, 8, 9, 10]

const judgeMethod = [
  {value: 1, label: 'Judges'},
  {value: 0, label: 'All participants'},
]
const cycleTermOptions = [
  {value: 'yearly', label: 'Yearly award'},
  {value: 'monthly', label: 'Monthly award'},
  {value: 'randomly', label: 'No set period'},
]

declare const defaultAward: AwardResource

const AwardForm = ({defaultAward}: {defaultAward: AwardResource}) => {
  const defaultMessage = {text: '', type: 'danger'}
  const [award, setAward] = useState<AwardResource>(defaultAward)
  const [message, setMessage] = useState(defaultMessage)
  const [hasCycles, setHasCycles] = useState(false)

  useEffect(() => {
    getHasCycles(award.id).then((resource) => {
      setHasCycles(resource.data.hasCycles)
    })
  }, [])

  const update = <T extends keyof AwardResource>(
    param: T,
    value: AwardResource[T]
  ) => {
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
      <Labeled columns={[4, 8]} required={true} label="Title">
        <Input
          value={award.title}
          update={(value: string) => update('title', value)}
          name="title"
          allowEmpty={false}
        />
      </Labeled>

      <Labeled columns={[4, 8]} required={true} label="Description">
        <Textarea
          value={award.description}
          update={(value: keyof AwardResource) => update('description', value)}
          columns={[4, 8]}
          name="description"
        />
      </Labeled>

      <Labeled
        columns={[4, 8]}
        required={true}
        label="Who determines the winner?">
        <ButtonGroup
          buttonClass="outline-primary"
          options={judgeMethod}
          value={award.judgeMethod}
          update={(value) => update('judgeMethod', value)}
        />
      </Labeled>

      <Labeled
        columns={[4, 8]}
        required={true}
        label="What time frame does this award represent?">
        <Select
          name="cycleTermOptions"
          disabled={hasCycles}
          options={cycleTermOptions}
          value={award.cycleTerm}
          update={(value) => update('cycleTerm', value)}
        />
        {hasCycles && (
          <div className="small text-danger">
            *Award currently has cycles. Cannot change term.
          </div>
        )}
      </Labeled>

      <div className="row">
        <div className="col-sm-9 mx-auto">
          <fieldset>
            <legend>Nominator</legend>
            <Labeled columns={[7, 2]} label="List the winner's nominator">
              <Checkbox
                value={award.creditNominator}
                update={(value) => update('creditNominator', value)}
              />
            </Labeled>
            <Labeled
              columns={[7, 2]}
              label="Nominators can nominate themselves for this award">
              <Checkbox
                value={award.selfNominate}
                update={(value) => update('selfNominate', value)}
              />
            </Labeled>
          </fieldset>
          <fieldset>
            <legend>References</legend>
            <Labeled columns={[7, 2]} label="References required">
              <Select
                name="referencesRequiredOptions"
                options={referencesRequiredOptions}
                value={award.referencesRequired}
                update={(value) => update('referencesRequired', value)}
              />
            </Labeled>

            <Labeled
              columns={[7, 2]}
              label="Reference support reasons required"
              info="Reference will be required to fill out a form or upload a supporting document.">
              <Checkbox
                value={award.referenceReasonRequired}
                update={(value) => update('referenceReasonRequired', value)}
              />
            </Labeled>
          </fieldset>
          <fieldset>
            <legend>Nominations</legend>
            <Labeled
              columns={[7, 2]}
              label="Nominator support reasons required"
              info="Reference will be required to fill out a form or upload a supporting document.">
              <Checkbox
                value={award.nominationReasonRequired}
                update={(value) => update('nominationReasonRequired', value)}
              />
            </Labeled>
            <Labeled
              columns={[7, 2]}
              label="Nominations must be approved before moving forward">
              <Checkbox
                value={award.approvalRequired}
                update={(value) => update('approvalRequired', value)}
              />
            </Labeled>
            <Labeled
              columns={[7, 2]}
              label="Notify a nominee of their nomination"
              info="Nominee will receive an email and be allowed to opt out.">
              <Checkbox
                value={award.tipNominated}
                update={(value) => update('tipNominated', value)}
              />
            </Labeled>
            <Labeled columns={[7, 2]} label="Award winners are shown publicly">
              <Checkbox
                value={award.publicView}
                update={(value) => update('publicView', value)}
              />
            </Labeled>
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

const container = document.getElementById('AwardForm') as HTMLElement
const root = createRoot(container)
root.render(<AwardForm {...{defaultAward}} />)
