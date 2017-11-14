// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import ReactBootstrapPagination from 'react-bootstrap/lib/Pagination';
import { Model } from '../models/model';

// import '../../css/style.css';



export default class Pagination extends React.Component {

  render() {

    let items = 0;
    let activePage = 1;
    let onSelect = null;


    // --------------------------------------------------
    //   モーダル / 通知
    // --------------------------------------------------

    if (this.props.type === 'notification') {

      if (this.props.notificationActiveType === 'unread') {

        items = Math.ceil(this.props.notificationUnreadTotal / this.props.notificationLimitNotification);
        activePage = this.props.notificationUnreadActivePage;

      } else {

        items = Math.ceil(this.props.notificationAlreadyReadTotal / this.props.notificationLimitNotification);
        activePage = this.props.notificationAlreadyReadActivePage;

      }

      onSelect = (
        e => this.props.funcSelectNotification(this.props.stateModel, null, this.props.notificationActiveType, e)
      );

    }


    // --------------------------------------------------
    //   表示するデータがない場合は空を返す
    // --------------------------------------------------

    if (items === 0) {
      return null;
    }


    return (
      <ReactBootstrapPagination
        key="pagination"
        className="pagination-margin"
        prev
        next
        first
        last
        ellipsis={false}
        boundaryLinks
        items={items}
        maxButtons={this.props.paginationColumn}
        activePage={activePage}
        onSelect={onSelect}
      />
    );
  }

}

Pagination.propTypes = {

  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  stateModel: PropTypes.instanceOf(Model).isRequired,
  paginationColumn: PropTypes.number.isRequired,


  // --------------------------------------------------
  //   タイプ
  // --------------------------------------------------

  type: PropTypes.string.isRequired,


  // --------------------------------------------------
  //   通知
  // --------------------------------------------------

  notificationActiveType: PropTypes.string.isRequired,
  notificationUnreadTotal: PropTypes.number.isRequired,
  notificationUnreadActivePage: PropTypes.number.isRequired,
  notificationAlreadyReadTotal: PropTypes.number.isRequired,
  notificationAlreadyReadActivePage: PropTypes.number.isRequired,
  notificationLimitNotification: PropTypes.number.isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcSelectNotification: PropTypes.func.isRequired,

};

Pagination.defaultProps = {

};
