'use strict'
import React, {useState, FormEvent, useRef} from 'react'
import PropTypes from 'prop-types'
import {nominateText} from '../../Share/NominationXHR'
import {nominateDocument} from '../../Share/DocumentXHR'
import Message from '../../Share/Message'
import {ParticipantResource, NominationResource} from '../../ResourceTypes'

interface Props {
  maxsize: number
  nomination: NominationResource
  participant: ParticipantResource
}

const ReasonForm = ({maxsize, nomination, participant}: Props) => {
  const [reasonText, setReasonText] = useState(nomination.reasonText)
  const [reasonFile, setReasonFile] = useState<File | null>(null)
  const [fileSelected, setFileSelected] = useState(false)
  const [uploadError, setUploadError] = useState(false)
  const [errorMessage, setErrorMessage] = useState('')
  const fileInput = useRef<HTMLInputElement>(null)

  const upload = (event: FormEvent<HTMLInputElement>) => {
    if (!event.currentTarget.files) {
      return
    }
    setReasonFile(event.currentTarget.files[0])
    setFileSelected(true)
  }

  const clearFile = () => {
    setUploadError(false)
    setErrorMessage('')
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
    nominateText(nomination.id, reasonText).then((response) => {
      if (response.data.success) {
        location.href = `./award/Participant/Nomination/${nomination.id}`
      }
    })
  }

  const submitDocumentNomination = () => {
    if (reasonFile === null) {
      return
    }

    nominateDocument(nomination.id, reasonFile)
      .then((response) => {
        console.log(response.data)
      })
      .catch((e) => {
        setUploadError(true)
        setErrorMessage(
          'An error occurred when uploading: ' + e.response.statusText
        )
      })
  }

  const maxSizeString = fileSize(maxsize)

  return (
    <div>
      <h4>Nomination Reason</h4>
      <p>
        This award requires you to supply your reason for selecting{' '}
        {participant.firstName} {participant.lastName} for nomination.
      </p>
      <p>Please type your reason below.</p>
      <textarea
        className="form-control mb-3"
        value={reasonText}
        onChange={(e) => setReasonText(e.target.value)}
      />
      <div className="text-center mb-5">
        <button
          disabled={reasonText.length == 0 || reasonFile != null}
          className="btn btn-success"
          onClick={submitTextNomination}>
          Submit reason above
        </button>
      </div>
      {uploadError && <Message message={errorMessage} type="danger" />}
      <div className="row mb-4">
        <div className="col-6">
          <p>
            Alternatively, you may upload a PDF file (less than {maxSizeString}{' '}
            in size) containing your reasoning.
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

ReasonForm.propTypes = {
  maxsize: PropTypes.number,
  nomination: PropTypes.object,
  participant: PropTypes.object,
}
export default ReasonForm
