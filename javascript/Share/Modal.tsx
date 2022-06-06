'use strict'
import React, {useEffect, ReactNode, Fragment} from 'react'
import PropTypes from 'prop-types'

type Props = {
  show: boolean
  title: string
  close: () => void
  size?: string
  button?: ReactNode
  children: ReactNode | ReactNode[]
}

const Backdrop = () => {
  return <div className="modal-backdrop fade show"></div>
}

const Modal = ({show, title, children, close, size, button}: Props) => {
  useEffect(() => {
    document.getElementsByTagName('body')[0].classList.add('modal-open')

    return () =>
      document.getElementsByTagName('body')[0].classList.remove('modal-open')
  }, [])

  let modalClass = 'modal fade'

  if (show) {
    modalClass += ' show'
  }
  let modalSize
  switch (size) {
    case 'lg':
    case 'sm':
    case 'xl':
      modalSize = 'modal-' + size
      break
  }

  return (
    <Fragment>
      {show && <Backdrop />}
      <div className={modalClass} style={{display: show ? 'block' : 'none'}}>
        <div className={`modal-dialog ${modalSize}`}>
          <div className="modal-content">
            <div className="modal-header">
              <div className="modal-title">
                <h3>{title}</h3>
              </div>
              <button type="button" className="close" onClick={close}>
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div className="modal-body">
              {children}
              <hr />
              <span className=" float-right">
                {button}
                <button className="btn btn-secondary ml-2" onClick={close}>
                  Close
                </button>
              </span>
            </div>
          </div>
        </div>
      </div>
    </Fragment>
  )
}

Modal.propTypes = {
  show: PropTypes.bool,
  close: PropTypes.func,
  title: PropTypes.string,
  size: PropTypes.string,
  children: PropTypes.node,
}
export default Modal
