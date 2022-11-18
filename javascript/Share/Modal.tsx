'use strict'
import React, {useEffect, ReactNode, Fragment} from 'react'
import PropTypes from 'prop-types'

type Props = {
  show: boolean
  includeCloseButton?: boolean
  title: string
  close: () => void
  size?: string
  button?: ReactNode
  children: ReactNode | ReactNode[]
}

const Backdrop = () => {
  return <div className="modal-backdrop fade show"></div>
}

const Modal = ({
  show,
  title,
  children,
  close,
  size,
  button,
  includeCloseButton,
}: Props) => {
  useEffect(() => {
    if (show) {
      document.getElementsByTagName('body')[0].classList.add('modal-open')
    } else {
      document.getElementsByTagName('body')[0].classList.remove('modal-open')
    }

    return () =>
      document.getElementsByTagName('body')[0].classList.remove('modal-open')
  }, [show])

  let modalClass = 'modal fade'

  if (show) {
    modalClass += ' show'
  }
  let modalSize = ''
  switch (size) {
    case 'lg':
    case 'sm':
    case 'xl':
      modalSize = 'modal-' + size
      break
  }

  return (
    <div>
      {show && <Backdrop />}
      <div className={modalClass} style={{display: show ? 'block' : 'none'}}>
        <div className={`modal-dialog ${modalSize}`}>
          <div className="modal-content">
            <div className="modal-header">
              <div className="modal-title">
                <h3 className="m-0">{title}</h3>
              </div>
              <button type="button" className="close" onClick={close}>
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div className="modal-body">
              {children}
              {button || includeCloseButton ? <hr /> : null}
              <span className=" float-right">
                {button}
                {includeCloseButton && (
                  <button className="btn btn-secondary ml-2" onClick={close}>
                    Close
                  </button>
                )}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

Modal.defaultProps = {
  includeCloseButton: true,
}

Modal.propTypes = {
  show: PropTypes.bool,
  includeCloseButton: PropTypes.bool,
  close: PropTypes.func.isRequired,
  title: PropTypes.string,
  size: PropTypes.string,
  children: PropTypes.node,
}
export default Modal
