// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import { ButtonGroup, Button, FormGroup, FormControl, Modal } from 'react-bootstrap';
// import { List, OrderedMap } from 'immutable';
import Masonry from 'react-masonry-component';
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
      <Modal show={this.props.modalNotificationShow} onHide={() => this.props.funcModalNotificationShow(false)} bsSize="lg">

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

          <Card {...this.props} dataType="notification" />
          {/* {this.codeBox()} */}

          {/* <h4>Overflowing text to show scroll behavior</h4>
          <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
          <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
          <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
          <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p> */}
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
          <Button className="close-button" onClick={() => this.props.funcModalNotificationShow(false)}>閉じる</Button>
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
