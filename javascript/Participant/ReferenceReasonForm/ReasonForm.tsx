'use strict'
import React, {useState, FormEvent, useRef} from 'react'
import PropTypes from 'prop-types'
import {referenceText} from '../../Share/ReferenceXHR'
import {
  deleteParticipantDocument,
  referenceDocument,
} from '../../Share/DocumentXHR'
import Message from '../../Share/Message'
import {fileSize} from '../../Share/FileSize'
import FileSelected from './FileSelected'
import {
  DocumentResource,
  ParticipantResource,
  ReferenceResource,
} from '../../ResourceTypes'

interface Props {
  maxsize: number
  reference: ReferenceResource
  participant: ParticipantResource
  currentReasonDocument: DocumentResource
}

const EmptyDocument = {
  id: 0,
  filename: '',
  nominationId: 0,
  referenceId: 0,
  title: '',
  created: '',
}

const ReasonForm = ({
  maxsize,
  reference,
  participant,
  currentReasonDocument,
}: Props) => {
  const [reasonText, setReasonText] = useState(reference.reasonText)
  const [reasonDocument, setReasonDocument] = useState<File | null>(null)
  const [fileSelected, setFileSelected] = useState(false)
  const [uploadError, setUploadError] = useState(false)
  const [errorMessage, setErrorMessage] = useState('')
  const [currentDocument, setCurrentDocument] = useState(currentReasonDocument)
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
      deleteParticipantDocument(currentDocument.id).then(() => {
        setCurrentDocument(EmptyDocument)
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

  const submitTextReference = () => {
    referenceText(reference.id, reasonText).then((response) => {
      if (response.data.success) {
        location.href = `./award/Participant/Participant/dashboard`
      }
    })
  }

  const showCurrentReasonDocument = () => {
    if (currentDocument.id > 0) {
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
              download={currentDocument.title}
              className="btn btn-primary btn-sm mb-2"
              href={`./award/Participant/Document/${currentDocument.id}/download`}>
              <strong>Download:</strong> {currentDocument.title}
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

  const submitDocumentReference = () => {
    if (reasonDocument === null) {
      return
    }

    referenceDocument(reference.id, reasonDocument)
      .then((response) => {
        if (response.data.success) {
          clearFile()
          setReasonDocument(response.data.documentId)
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

  const maxSizeString = fileSize(maxsize)

  return (
    <div>
      <h4>Reference Reason</h4>
      <p>
        This award requires you to supply your reason for selecting{' '}
        {participant.firstName} {participant.lastName} for reference.
      </p>
      <p>Please type your reason below.</p>
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
          onClick={submitTextReference}>
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
          <input
            type="file"
            name="file"
            onChange={upload}
            ref={fileInput}
            accept="application/pdf"
          />
        </div>
        <div className="col-6">
          {showCurrentReasonDocument()}
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
          onClick={submitDocumentReference}>
          Submit document as reason
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
