'use strict'
import React, {useState, FormEvent, useRef} from 'react'
import PropTypes from 'prop-types'
import {nominateText, nominateDocument} from '../../Share/NominationXHR'
import {CycleResource, ParticipantResource} from '../../ResourceTypes'

declare const cycle: CycleResource
declare const participant: ParticipantResource

type Props = {firstName: string; maxsize: number}

const ReasonForm = ({firstName, maxsize}: Props) => {
  const [reasonText, setReasonText] = useState('')
  const [reasonFile, setReasonFile] = useState<File | null>(null)
  const [fileSelected, setFileSelected] = useState(false)
  const fileInput = useRef<HTMLInputElement>(null)

  const upload = (event: FormEvent<HTMLInputElement>) => {
    if (!event.currentTarget.files) {
      return
    }
    setReasonFile(event.currentTarget.files[0])
    setFileSelected(true)
  }

  const clearFile = () => {
    setReasonFile(null)
    setFileSelected(false)
    if (fileInput.current) {
      fileInput.current.value = ''
    }
  }

  const fileSize = (size: number) => {
    if (size < 1000) {
      return size + ' bytes'
    } else if (size >= 1000000) {
      return Math.floor((size / 1000000) * 10) / 10 + 'MB'
    } else if (size >= 1000) {
      return Math.floor((size / 1000) * 10) / 10 + 'KB'
    }
  }

  const wrongFormat =
    reasonFile === null ? false : reasonFile.type.match(/\/pdf/) === null

  const fileTooBig = reasonFile === null ? false : reasonFile.size > maxsize

  const submitTextNomination = () => {
    nominateText(participant.id, cycle.id, reasonText).then((response) => {
      console.log(response)
    })
  }

  const submitDocumentNomination = () => {
    if (reasonFile === null) {
      return
    }

    nominateDocument(participant.id, cycle.id, reasonFile).then((response) => {
      console.log(response)
    })
  }

  const maxSizeString = fileSize(maxsize)

  return (
    <div>
      <h4>Nomination Reason</h4>
      <p>
        Please type your reason for nominating{' '}
        <strong className="text-primary">{firstName}</strong> below.
      </p>
      <textarea
        className="form-control mb-3"
        value={reasonText}
        onChange={(e) => setReasonText(e.target.value)}
      />
      <div className="text-center">
        <button
          disabled={reasonText.length == 0 || reasonFile != null}
          className="btn btn-success"
          onClick={submitTextNomination}>
          Submit reason above
        </button>
      </div>
      <div className="row my-3">
        <div className="col-sm-5">
          <hr />
        </div>
        <div className="col-sm-2 text-center">or</div>
        <div className="col-sm-5">
          <hr />
        </div>
      </div>
      <div className="row mb-4">
        <div className="col-6">
          <p>
            You may upload a PDF file (less than {maxSizeString} in size)
            instead.
          </p>
          <input type="file" name="file" onChange={upload} ref={fileInput} />
        </div>
        <div className="col-6">
          {fileSelected && reasonFile?.name ? (
            <div>
              <p>
                <strong>Filename:</strong> {reasonFile.name}
                <br />
                <strong>Filetype:</strong>{' '}
                <span className={wrongFormat ? 'text-danger' : 'text-success'}>
                  {reasonFile.type}{' '}
                  {wrongFormat ? (
                    <span className="badge badge-danger">
                      File is not a PDF
                    </span>
                  ) : null}
                </span>
                <br />
                <strong>Size in bytes:</strong>{' '}
                <span className={fileTooBig ? 'text-danger' : 'text-success'}>
                  {fileSize(reasonFile.size)}{' '}
                  {fileTooBig ? (
                    <span className="badge badge-danger">
                      File size exceeds {maxSizeString}
                    </span>
                  ) : null}
                </span>
              </p>
              <button className="btn btn-danger btn-sm" onClick={clearFile}>
                Clear file
              </button>
            </div>
          ) : null}
        </div>
      </div>
      <div className="text-center">
        <button
          disabled={reasonFile == null || wrongFormat || fileTooBig}
          className="btn btn-success"
          onClick={submitDocumentNomination}>
          Submit document as reason
        </button>
      </div>
    </div>
  )
}

ReasonForm.propTypes = {firstName: PropTypes.string}
export default ReasonForm