'use strict'
import React, {useState, FormEvent, useRef} from 'react'
import PropTypes from 'prop-types'
import {
  postReasonText,
  putReasonText,
  removeReasonDocument,
} from '../../Share/ReasonXHR'
import {
  deleteParticipantDocument,
  uploadDocument,
} from '../../Share/DocumentXHR'
import Message from '../../Share/Message'
import {fileSize} from '../../Share/FileSize'
import FileSelected from './FileSelected'
import {DocumentResource, ReasonResource} from '../../ResourceTypes'

interface Props {
  currentDocument: DocumentResource
  maxsize: number
  nominatedName: string
  reason: ReasonResource
}

const EmptyDocument = {
  id: 0,
  filename: '',
  nominationId: 0,
  referenceId: 0,
  title: '',
  created: '',
}

/**
 * Used to forward the user if they refresh/delete the reason.
 * @param reason
 */
const forwardToNewForm = (reason: ReasonResource) => {
  if (reason.reasonType === 0) {
    window.location.href =
      './award/Participant/Reason/createNomination/?nominationId=' +
      reason.nominationId
  } else {
    window.location.href =
      './award/Participant/Reason/createReference/?referenceId=' +
      reason.referenceId
  }
}

const ReasonForm = ({
  currentDocument,
  maxsize,
  nominatedName,
  reason,
}: Props) => {
  const [document, setDocument] = useState(currentDocument)
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

  const deleteCurrentDocument = () => {
    if (
      confirm(
        'Are you sure you want to permanently remove your current endorsement document?'
      )
    ) {
      removeReasonDocument(reason.id).then((response) => {
        if (response.data.success) {
          if (response.data.reasonDeleted) {
            forwardToNewForm(reason)
          } else {
            setDocument(EmptyDocument)
          }
        }
      })
    }
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

  const submitText = () => {
    if (reason.id > 0) {
      putReasonText(reason.id, reasonText).then((response) => {
        if (response.data.success) {
          location.href = `./award/Participant/Participant/dashboard`
        }
      })
    } else {
      reason.reasonText = reasonText
      postReasonText(reason).then((response) => {
        if (response.data.success) {
          location.href = `./award/Participant/Participant/dashboard`
        }
      })
    }
  }

  const submitDocument = () => {
    if (reasonDocument === null) {
      return
    }

    uploadDocument(reason, reasonDocument)
      .then((response) => {
        if (response.data.success) {
          location.href = './award/Participant/Participant/dashboard'
        } else {
          setUploadError(true)
          setErrorMessage(
            'An error occurred when uploading:' + response.data.error
          )
        }
      })
      .catch((e) => {
        setUploadError(true)
        setErrorMessage(
          'An error occurred when uploading: ' + e.response.statusText
        )
      })
  }
  const showCurrentDocument = () => {
    if (document.id > 0) {
      if (reasonDocument) {
        return (
          <div className="badge badge-danger">
            Warning: this document will replace your previously uploaded
            document.
          </div>
        )
      } else {
        return (
          <div>
            <a
              download={document.title}
              className="btn btn-primary btn-sm mb-2"
              href={`./award/Participant/Document/${document.id}/download`}>
              <strong>Download:</strong> {document.title}
            </a>
            <button
              className="btn btn-danger btn-sm"
              onClick={deleteCurrentDocument}>
              Delete endorsement document
            </button>
          </div>
        )
      }
    }
  }

  const maxSizeString = fileSize(maxsize)

  const title = reason.id === 0 ? 'Add reason' : 'Update reason'
  const reasonType = reason.reasonType === 0 ? 'nomination' : 'reference'
  return (
    <div>
      <h4>{title}</h4>
      <p>
        This award requires each {reasonType} to include a written endorsement.
        Please type or upload your support for {nominatedName} below.
      </p>
      <textarea
        className="form-control mb-3"
        style={{minHeight: '200px'}}
        value={reasonText}
        onChange={(e) => setReasonText(e.target.value)}
      />
      <div className="text-center mb-5">
        <button
          disabled={reasonText.length == 0 || reasonDocument != null}
          className="btn btn-success"
          onClick={submitText}>
          Submit endorsement above
        </button>
      </div>
      {uploadError && <Message message={errorMessage} type="danger" />}
      <div className="row mb-4">
        <div className="col-6">
          <p>
            Upload your endorsement in a PDF file (less than {maxSizeString} in
            size).
          </p>
          <input
            type="file"
            name="file"
            onChange={upload}
            ref={fileInput}
            accept="application/pdf"
          />
        </div>
        <div className="col-6">
          {showCurrentDocument()}
          {fileSelected && reasonDocument?.name && (
            <FileSelected
              {...{
                reasonDocument,
                wrongFormat,
                fileSize,
                fileTooBig,
                maxSizeString,
                clearFile,
              }}
            />
          )}
        </div>
      </div>
      <div className="text-center">
        <button
          disabled={reasonDocument == null || wrongFormat || fileTooBig}
          className="btn btn-success"
          onClick={submitDocument}>
          Submit document as endorsement
        </button>
      </div>
    </div>
  )
}

ReasonForm.propTypes = {
  maxsize: PropTypes.number,
  reference: PropTypes.object,
  participant: PropTypes.object,
}
export default ReasonForm
