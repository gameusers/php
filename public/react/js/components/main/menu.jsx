// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import { Link, Redirect } from 'react-router-dom';
// import { FormGroup, FormControl } from 'react-bootstrap';
import { List } from 'immutable';
import { CSSTransitionGroup } from 'react-transition-group'
// import { slide as Menu } from 'react-burger-menu';
// import Masonry from 'react-masonry-component';
import { Model } from '../../models/model';
// import ModalNotification from './modal/notification';

import '../../../css/style.css';



export default class MainMenu extends React.Component {

  constructor() {

    super();

    this.list = List([
      {
        group: 'app',
        content: 'share-buttons',
        materialIcon: 'cloud_queue',
        text: 'シェアボタン'
      },
      {
        group: 'app',
        content: 'test1',
        materialIcon: 'forum',
        text: 'テスト1'
      },
      {
        group: 'app',
        content: 'test2',
        materialIcon: 'priority_high',
        text: 'テスト2'
      },
      {
        group: 'app',
        content: 'test3',
        materialIcon: 'group',
        text: 'テスト3'
      }
    ]);

  }


  /**
   * PC用
   * @return {string} コード
   */
  codeOther() {

    const codeArr = [];


    this.list.entrySeq().forEach((e) => {

      const key = e[0];
      const value = e[1];


      // --------------------------------------------------
      //   色の種類が11種類しかないので、それ以上の場合はループする
      //   綺麗な処理ではない
      // --------------------------------------------------

      let borderRightType = key + 1;

      if (borderRightType > 22) {
        borderRightType -= 22;
      } else if (borderRightType > 11) {
        borderRightType -= 11;
      }


      // --------------------------------------------------
      //   最後の行は margin-bottom を 0 にする
      // --------------------------------------------------

      let styleBox = null;

      if (key + 1 === this.list.count()) {
        styleBox = { marginBottom: 0 };
      }


      codeArr.push(
        <Link
          className="card-link"
          to={`/${value.group}/${value.content}`}
          onClick={() => this.props.funcUrlDirectory(value.group, value.content, null)}
          key={key}
        >
          <div className={`box border-right-${borderRightType}`} style={styleBox}>
            <div className="left"><i className="material-icons">{value.materialIcon}</i></div>
            <div className="right">{value.text}</div>
            {this.props.urlDirectory1 === value.group && this.props.urlDirectory2 === value.content &&
              <div className="selected-icon">
                <div className="spinner">
                  <div className="rect1" />
                  <div className="rect2" />
                  <div className="rect3" />
                  <div className="rect4" />
                  <div className="rect5" />
                </div>
              </div>
            }
          </div>
        </Link>
      );

    });

    return codeArr;

  }


  /**
   * スマートフォン・タブレット用
   * @return {string} コード
   */
  codeMobile() {

    const codeArr = [];


    this.list.entrySeq().forEach((e) => {

      const key = e[0];
      const value = e[1];


      // codeArr.push(
      //   <a id="home" className="menu-item" href="/">{value.text}</a>
      // );


      // codeArr.push(
      //   <div
      //     className="card-link"
      //     // style={{ outline: 'none' }}
      //     // push
      //     // to={`/${value.group}/${value.content}`}
      //     // onClick={() => this.props.funcDrawerMenuActive()}
      //     onClick={() => this.props.funcUrlDirectory(value.group, value.content, null)}
      //     role="button"
      //     tabIndex="0"
      //     key={key}
      //   >
      //     <div className="box">
      //       <div className="left"><i className="material-icons">{value.materialIcon}</i></div>
      //       <div className="right"><span className="selected">{value.text}</span></div>
      //     </div>
      //   </div>
      // );

      codeArr.push(
        <Link
          className="card-link"
          to={`/${value.group}/${value.content}`}
          // onClick={() => this.props.funcDrawerMenuActive()}
          onClick={() => this.props.funcUrlDirectory(value.group, value.content, null)}
          key={key}
        >
          {/* <div className="box" onClick={() => this.props.funcDrawerMenuActive()} role="button" tabIndex="0"> */}
          <div className="box">
            <div className="left"><i className="material-icons">{value.materialIcon}</i></div>
            <div className="right"><span className="selected">{value.text}</span></div>
          </div>
        </Link>
      );

    });

    return codeArr;

  }


  render() {


    // --------------------------------------------------
    //   PC
    // --------------------------------------------------

    if (this.props.deviceType === 'other') {

      return (
        <nav className="menu">
          <div className="slide">
            {/* <div className="ad" /> */}
            {this.codeOther()}
          </div>
        </nav>
      );

    }

    // --------------------------------------------------
    //   スマートフォン・タブレット
    // --------------------------------------------------

    // console.log('this.props.drawerMenuActive = ', this.props.drawerMenuActive);

    return (
      <nav>

        <CSSTransitionGroup
          transitionName="drawer-menu"
          // transitionAppear
          transitionEnterTimeout={500}
          transitionLeaveTimeout={300}
          // transitionAppearTimeout={300}
          component="div"
        >
          {this.props.drawerMenuActive &&
            <div className="drawer-menu" key="drawer-menu">
              <div className="title">Menu</div>
              {this.codeMobile()}
            </div>
          }
        </CSSTransitionGroup>

        <CSSTransitionGroup
          transitionName="drawer-overlay"
          // transitionAppear
          transitionEnterTimeout={500}
          transitionLeaveTimeout={300}
          // transitionAppearTimeout={300}
          component="div"
        >
          {this.props.drawerMenuActive &&
            <div
              className="drawer-overlay"
              onClick={() => this.props.funcDrawerMenuActive()}
              role="menuitem"
              tabIndex="0"
              key="drawer-overlay"
            />
          }
        </CSSTransitionGroup>

      </nav>
    );


    // {this.props.drawerMenuActive &&
    //   <div key="drawer-menu">
    //     <div className="title">Menu</div>
    //     {this.codeMobile()}
    //   </div>
    // }

    // return (
    //   // <div />
    //   <Menu isOpen>
    //     <a id="home" className="menu-item" href="/">Home</a>
    //     <a id="about" className="menu-item" href="/about">About</a>
    //     <a id="contact" className="menu-item" href="/contact">Contact</a>
    //     {this.codeMobile()}
    //   </Menu>
    // );

    // return (
    //   <nav className="slideMenu" id="slideMenu">
    //     <div className="title">Menu</div>
    //     {this.codeMobile()}
    //   </nav>
    // );

    // return (
    //   <nav>
    //     <div className="drawer-menu">
    //       <div className="title">Menu</div>
    //       {this.codeMobile()}
    //     </div>
    //     <div
    //       className="drawer-overlay"
    //       onClick={() => this.props.funcDrawerMenuActive()}
    //       role="menuitem"
    //       tabIndex="0"
    //     />
    //   </nav>
    // );

  }

}

MainMenu.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  stateModel: PropTypes.instanceOf(Model).isRequired,

  deviceType: PropTypes.string.isRequired,
  urlDirectory1: PropTypes.string,
  urlDirectory2: PropTypes.string,
  // urlBase: PropTypes.string.isRequired,

  // csrfToken: PropTypes.string.isRequired,


  // --------------------------------------------------
  //   ドロワーメニュー
  // --------------------------------------------------

  drawerMenuActive: PropTypes.bool.isRequired,
  // footerCardGameCommunityRenewalList: PropTypes.instanceOf(List),
  // footerCardGameCommunityAccessList: PropTypes.instanceOf(List),
  // footerCardUserCommunityAccessList: PropTypes.instanceOf(List),





  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcUrlDirectory: PropTypes.func.isRequired,

  funcDrawerMenuActive: PropTypes.func.isRequired,
  // funcSelectFooterCardType: PropTypes.func.isRequired,


};

MainMenu.defaultProps = {

  urlDirectory1: null,
  urlDirectory2: null,

  footerCardGameCommunityRenewalList: null,
  footerCardGameCommunityAccessList: null,
  footerCardUserCommunityAccessList: null

};
