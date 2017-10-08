// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
// import Sticky from 'react-sticky-state';



/**
 * ボトムボタン / スマートフォン・タブレット版
 * ページ内移動・ドロワーメニュー起動
 */
const MainMenuButtons = (props) => {

  if (props.deviceType === 'other') {
    return null;
  }


  // --------------------------------------------------
  //   ページ上部に移動
  // --------------------------------------------------

  const funcMoveTop = () => {
    const selector = document.querySelector('.main-s');
    const clientRect = selector.getBoundingClientRect();
    const top = clientRect.top;
    const pageY = window.pageYOffset + top;
    scrollTo(0, pageY - 50);
  };


  // --------------------------------------------------
  //   ページ下部に移動
  // --------------------------------------------------

  const funcMoveBottom = () => {
    const selector = document.querySelector('footer');
    const clientRect = selector.getBoundingClientRect();
    const top = clientRect.top;
    const pageY = window.pageYOffset + top;
    scrollTo(0, pageY);
  };


  return (
    <div className="menu-s">

      <div className="icon-box">

        <div className="icon-arrow-box">

          <div
            className="icon"
            onClick={() => funcMoveTop()}
            role="button"
            tabIndex="0"
          >
            <span className="glyphicon glyphicon-triangle-top icon-arrow" aria-hidden="true" />
          </div>

          <div
            className="icon"
            onClick={() => funcMoveBottom()}
            role="button"
            tabIndex="0"
          >
            <span className="glyphicon glyphicon-triangle-bottom icon-arrow" aria-hidden="true" />
          </div>

          <div>ページ内移動</div>

        </div>

        <div className="icon-menu-box">

          <div
            className="icon"
            onClick={() => props.funcMenuDrawerActive()}
            role="button"
            tabIndex="0"
          >
            <span className="glyphicon glyphicon-list-alt icon-menu" aria-hidden="true" />
          </div>

          <div>メニュー</div>

        </div>

      </div>

    </div>
  );

};

MainMenuButtons.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  deviceType: PropTypes.string.isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcMenuDrawerActive: PropTypes.func.isRequired,


};

MainMenuButtons.defaultProps = {

};

export default MainMenuButtons;
