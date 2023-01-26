'use strict'
import React, {useState, FormEvent, useRef} from 'react'
import PropTypes from 'prop-types'
import {nominateText} from '../../Share/NominationXHR'
import {uploadDocument} from '../../Share/DocumentXHR'
import Message from '../../Share/Message'
import {fileSize} from '../../Share/FileSize'
import {
  ParticipantResource,
  NominationResource,
  ReasonResource,
} from '../../ResourceTypes'
import {AxiosError} from 'axios'

interface Props {
  maxsize: number
  nomination: NominationResource
  participant: ParticipantResource
  reason: ReasonResource
}

const ReasonForm = ({maxsize, nomination, participant, reason}: Props) => {
  const [reasonText, setReasonText] = useState(reason.reasonText)
  const [reasonDocument, setReasonDocument] = useState<File | null>(null)
  const [fileSelected, setFileSelected] = useState(false)
  const [uploadError, setUploadError] = useState(false)
  const [errorMessage, setErrorMessage] = useState('')
  const fileInput = useRef<HTMLInputElement>(null)

  const upload = (event: FormEvent<HTMLInputElement>) => {
    if (!event.currentTarget.files) {
      return
    }
    setReasonDocument(event.currentTarget.files[0])
    setFileSelected(true)
  }

  const clearFile = () => {
    setUploadError(false)
    setErrorMessage('')
    setReasonDocument(null)
    setFileSelected(false)
    if (fileInput.current) {
      fileInput.current.value = ''
    }
  }

  const wrongFormat =
    reasonDocument === null
      ? false
      : reasonDocument.type.match(/\/pdf/) === null

  const fileTooBig =
    reasonDocument === null ? false : reasonDocument.size > maxsize

  const submitTextNomination = () => {
    nominateText(nomination.id, reasonText).then((response) => {
      if (response.data.success) {
        location.href = `./award/Participant/Nomination/${nomination.id}`
      }
    })
  }

  const submitDocumentNomination = () => {
    if (reasonDocument === null) {
      return
    }

    uploadDocument(reason, reasonDocument)
      .then((response) => {
        alert(response.data)
      })
      .catch((e: AxiosError) => {
        setUploadError(true)
        if (e.response !== undefined) {
          setErrorMessage(
            'An error occurred when uploading: ' + e.response.statusText
          )
        }
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
          disabled={reasonText.length == 0 || reasonDocument != null}
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
          {fileSelected && reasonDocument?.name ? (
            <div>
              <p>
                <strong>Filename:</strong> {reasonDocument.name}
                <br />
                <strong>Filetype:</strong>{' '}
                <span className={wrongFormat ? 'text-danger' : 'text-success'}>
                  {reasonDocument.type}{' '}
                  {wrongFormat ? (
                    <span className="badge badge-danger">
                      File is not a PDF
                    </span>
                  ) : null}
                </span>
                <br />
                <strong>Size in bytes:</strong>{' '}
                <span className={fileTooBig ? 'text-danger' : 'text-success'}>
                  {fileSize(reasonDocument.size)}{' '}
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
          disabled={reasonDocument == null || wrongFormat || fileTooBig}
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
