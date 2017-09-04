// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import { ButtonGroup, Button, FormGroup, FormControl, Modal } from 'react-bootstrap';
// import { List, OrderedMap } from 'immutable';
import Pagination from '../pagination';
import { Model } from '../../models/model';
import Card from '../card';

import '../../../css/style.css';





export default class ModalNotification extends React.Component {

  codeBox() {

    let codeArr = [];

    codeArr = (
      <div className="notification-box">

        <div className="left-box">
          AAA
        </div>

        <div className="right-box">
          BBB
        </div>

      </div>
    );

    return codeArr;

  }



  render() {
    return (
      <Modal show={this.props.modalNotificationShow} onHide={() => this.props.funcModalNotificationShow(this.props.stateModel, false)} bsSize="lg">


        <Modal.Header closeButton className="modal-notification-buttons">
          <div className="modal-notification-box">
            <ButtonGroup>
              <Button
                bsStyle="default"
                className="ladda-button"
                data-style="slide-right"
                // data-size="s"
                data-spinner-color="#000000"
                active
                // onClick={e => this.props.funcAjaxDeleteTheme(this.props.stateModel, e.currentTarget, themeNameId)}
              >
                <span className="ladda-label">未読</span>
              </Button>
              <Button
                bsStyle="default"
                className="ladda-button"
                data-style="slide-right"
                // data-size="s"
                data-spinner-color="#000000"
                // onClick={e => this.props.funcAjaxDeleteTheme(this.props.stateModel, e.currentTarget, themeNameId)}
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
            className="ladda-button all-already-read-button"
            data-style="slide-right"
            // data-size="s"
            data-spinner-color="#000000"
            // onClick={e => this.props.funcAjaxDeleteTheme(this.props.stateModel, e.currentTarget, themeNameId)}
          >
            <span className="ladda-label">すべて既読にする</span>
          </Button>
          <Button className="close-button" onClick={() => this.props.funcModalNotificationShow(this.props.stateModel, false)}>閉じる</Button>
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
  // urlBase: PropTypes.string.isRequired,
  // csrfToken: PropTypes.string.isRequired,


  // --------------------------------------------------
  //   モーダル
  // --------------------------------------------------

  modalNotificationShow: PropTypes.bool.isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcModalNotificationShow: PropTypes.func.isRequired,

};

ModalNotification.defaultProps = {

  // footerCardGameCommunityRenewalList: null,
  // footerCardGameCommunityAccessList: null,
  // footerCardUserCommunityAccessList: null

};
