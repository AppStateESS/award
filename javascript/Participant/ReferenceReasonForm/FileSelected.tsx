'use strict'
import React from 'react'
import PropTypes from 'prop-types'

type Props = {
  reasonDocument: File
  wrongFormat: boolean
  fileSize: (size: number) => string | undefined
  fileTooBig: boolean
  maxSizeString: string
  clearFile: () => void
}
const FileSelected = ({
  reasonDocument,
  wrongFormat,
  fileSize,
  fileTooBig,
  maxSizeString,
  clearFile,
}: Props) => {
  return (
    <div>
      <p>
        <strong>File name:</strong> {reasonDocument.name}
        <br />
        <strong>File type:</strong>{' '}
        <span className={wrongFormat ? 'text-danger' : 'text-success'}>
          {reasonDocument.type}{' '}
          {wrongFormat ? (
            <span className="badge badge-danger">File is not a PDF</span>
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
  )
}

FileSelected.propTypes = {
  reasonDocument: PropTypes.object,
  wrongFormat: PropTypes.bool,
  fileSize: PropTypes.func,
  fileTooBig: PropTypes.bool,
  maxSizeString: PropTypes.string,
  clearFile: PropTypes.func,
}
export default FileSelected
