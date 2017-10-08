// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import { ButtonGroup, Button, Modal } from 'react-bootstrap';
import Pagination from '../pagination';
import { Model } from '../../models/model';
import Card from '../card';

// import '../../../css/style.css';





export default class ModalNotification extends React.Component {

  render() {
    return (
      <Modal
        show={this.props.modalNotificationShow}
        onHide={() => this.props.funcHideModalNotification(this.props.stateModel)}
        bsSize="lg"
      >


        <Modal.Header closeButton className="modal-notification-buttons">
          <div className="modal-notification-box">
            <ButtonGroup>
              <Button
                bsStyle="default"
                className="ladda-button"
                data-style="slide-right"
                data-spinner-color="#000000"
                active={this.props.notificationActiveType === 'unread' || false}
                onClick={e => this.props.funcSelectNotification(
                  this.props.stateModel,
                  e.currentTarget,
                  'unread',
                  this.props.notificationUnreadActivePage
                )}
              >
                <span className="ladda-label">未読</span>
              </Button>
              <Button
                bsStyle="default"
                className="ladda-button"
                data-style="slide-right"
                data-spinner-color="#000000"
                active={this.props.notificationActiveType === 'alreadyRead' || false}
                onClick={e => this.props.funcSelectNotification(
                  this.props.stateModel,
                  e.currentTarget,
                  'alreadyRead',
                  this.props.notificationAlreadyReadActivePage
                )}
              >
                <span className="ladda-label">既読</span>
              </Button>
            </ButtonGroup>
          </div>
        </Modal.Header>


        <Modal.Body className="modal-body">
          <Card {...this.props} type="notification" />
          <Pagination {...this.props} type="notification" />
        </Modal.Body>


        <Modal.Footer bsClass="modal-notification-footer">
          <Button
            bsStyle="default"
            className="ladda-button"
            data-style="expand-right"
            data-spinner-color="#000000"
            onClick={e => this.props.funcUpdateAllUnreadToAlreadyRead(this.props.stateModel, e.currentTarget)}
            style={{ outline: 'none' }}
          >
            <span className="ladda-label">すべて既読にする</span>
          </Button>
          <Button
            className="close-button"
            onClick={() => this.props.funcHideModalNotification(this.props.stateModel)}
          >
            閉じる
          </Button>
        </Modal.Footer>


      </Modal>
    );
  }

}

ModalNotification.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  stateModel: PropTypes.instanceOf(Model).isRequired,


  // --------------------------------------------------
  //   モーダル
  // --------------------------------------------------

  modalNotificationShow: PropTypes.bool.isRequired,


  // --------------------------------------------------
  //   通知
  // --------------------------------------------------

  notificationActiveType: PropTypes.string.isRequired,
  notificationUnreadActivePage: PropTypes.number.isRequired,
  notificationAlreadyReadActivePage: PropTypes.number.isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcHideModalNotification: PropTypes.func.isRequired,
  funcSelectNotification: PropTypes.func.isRequired,
  funcUpdateAllUnreadToAlreadyRead: PropTypes.func.isRequired,


};

ModalNotification.defaultProps = {

};
