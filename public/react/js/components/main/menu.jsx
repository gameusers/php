// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';
// import { FormGroup, FormControl } from 'react-bootstrap';
import { List, Map } from 'immutable';
// import { CSSTransitionGroup } from 'react-transition-group'
// import Transition from 'react-transition-group/Transition';
// import CSSTransition from 'react-transition-group/CSSTransition';
import { VelocityComponent, VelocityTransitionGroup } from 'velocity-react';
// import { slide as Menu } from 'react-burger-menu';
// import Masonry from 'react-masonry-component';
// import { Model } from '../../models/model';
// import ModalNotification from './modal/notification';

import '../../../css/style.css';



export default class MainMenu extends React.Component {

  constructor() {
    // console.log('constructor');
    super();


    this.list = List();

  }



  // componentDidMount() {
  //
  //   // console.log('componentDidMount');
  //
  //   // console.log(this.props.menuMap.has(this.props.urlDirectory1));
  //
  //
  // }

  // componentWillUpdate() {
  //
  //   console.log('componentWillUpdate');
  //
  //   if (this.props.menuMap.hasIn([this.props.urlDirectory1, this.props.urlDirectory2])) {
  //     this.list = this.props.menuMap.getIn([this.props.urlDirectory1, this.props.urlDirectory2]);
  //     // console.log('this.props.menuMap = ', this.props.menuMap.toJS());
  //     // console.log('this.list = ', this.list.toJS());
  //   }
  //
  // }



  /**
   * PC用
   * @return {array} コードの配列
   */
  codeOther() {

    const codeArr = [];


    this.list.entrySeq().forEach((e) => {

      const key = e[0];
      const value = e[1];


      // --------------------------------------------------
      //   リンク
      // --------------------------------------------------

      let linkTo = '';
      if (value.get('urlDirectory1')) linkTo += `/${value.get('urlDirectory1')}`;
      if (value.get('urlDirectory2')) linkTo += `/${value.get('urlDirectory2')}`;
      if (value.get('urlDirectory3')) linkTo += `/${value.get('urlDirectory3')}`;


      // --------------------------------------------------
      //   色の種類が11種類しかないので、それ以上の場合はループする
      //   スマートな書き方ではありません…
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


      // --------------------------------------------------
      //   Active
      // --------------------------------------------------

      let booleanActive = false;

      if (
        value.get('urlDirectory1') === this.props.urlDirectory1 &&
        value.get('urlDirectory2') === this.props.urlDirectory2 &&
        value.get('urlDirectory3') === this.props.urlDirectory3
      ) {
        booleanActive = true;
      }



      codeArr.push(
        <Link
          className="card-link"
          to={linkTo}
          onClick={() => this.props.funcUrlDirectory(value.get('urlDirectory1'), value.get('urlDirectory2'), value.get('urlDirectory3'))}
          key={key}
        >
          <div className={`box border-right-${borderRightType}`} style={styleBox}>
            <div className="left"><i className="material-icons">{value.get('materialIcon')}</i></div>
            <div className="right">{value.get('text')}</div>
            {booleanActive &&
              <div className="active-icon">
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
   * @return {array} コードの配列
   */
  codeMobile() {

    const codeArr = [];


    this.list.entrySeq().forEach((e) => {

      const key = e[0];
      const value = e[1];


      // --------------------------------------------------
      //   リンク
      // --------------------------------------------------

      let linkTo = '';
      if (value.get('urlDirectory1')) linkTo += `/${value.get('urlDirectory1')}`;
      if (value.get('urlDirectory2')) linkTo += `/${value.get('urlDirectory2')}`;
      if (value.get('urlDirectory3')) linkTo += `/${value.get('urlDirectory3')}`;


      // --------------------------------------------------
      //   Active
      // --------------------------------------------------

      let classActive = null;

      if (
        value.get('urlDirectory1') === this.props.urlDirectory1 &&
        value.get('urlDirectory2') === this.props.urlDirectory2 &&
        value.get('urlDirectory3') === this.props.urlDirectory3
      ) {
        classActive = 'active';
      }


      codeArr.push(
        <Link
          className="card-link"
          to={linkTo}
          onClick={() => this.props.funcUrlDirectory(value.get('urlDirectory1'), value.get('urlDirectory2'), value.get('urlDirectory3'))}
          key={key}
        >
          <div className="box">
            <div className="left"><i className="material-icons">{value.get('materialIcon')}</i></div>
            <div className="right"><span className={classActive}>{value.get('text')}</span></div>
          </div>
        </Link>
      );

    });

    return codeArr;

  }



  render() {

    // console.log('this.props.urlDirectory1 = ', this.props.urlDirectory1);
    // console.log('this.props.urlDirectory2 = ', this.props.urlDirectory2);
    // console.log('this.props.urlDirectory3 = ', this.props.urlDirectory3);


    if (this.props.menuMap.hasIn([this.props.urlDirectory1, this.props.urlDirectory2])) {
      this.list = this.props.menuMap.getIn([this.props.urlDirectory1, this.props.urlDirectory2]);
      // console.log('this.props.menuMap = ', this.props.menuMap.toJS());
      // console.log('this.list = ', this.list.toJS());
    }


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

    return (
      <nav>

        <VelocityComponent
          animation={{ translateX: this.props.menuDrawerActive ? 0 : -250 }}
          duration={300}
        >
          <div className="drawer-menu" key="drawer-menu">
            <div className="title">Menu</div>
            {this.codeMobile()}
          </div>
        </VelocityComponent>

        <VelocityTransitionGroup
          enter={{ animation: 'fadeIn', duration: 300 }}
          leave={{ animation: 'fadeOut', duration: 300 }}
        >
          {this.props.menuDrawerActive ?
            <div
              className="drawer-overlay"
              onClick={() => this.props.funcMenuDrawerActive()}
              role="menuitem"
              tabIndex="0"
              key="drawer-overlay"
            />
            : undefined}
        </VelocityTransitionGroup>

      </nav>
    );

  }

}

MainMenu.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  // stateModel: PropTypes.instanceOf(Model).isRequired,

  deviceType: PropTypes.string.isRequired,
  urlDirectory1: PropTypes.string,
  urlDirectory2: PropTypes.string,
  urlDirectory3: PropTypes.string,
  // urlBase: PropTypes.string.isRequired,

  // csrfToken: PropTypes.string.isRequired,


  // --------------------------------------------------
  //   メニュー
  // --------------------------------------------------

  menuMap: PropTypes.instanceOf(Map).isRequired,
  menuDrawerActive: PropTypes.bool.isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcUrlDirectory: PropTypes.func.isRequired,

  funcMenuDrawerActive: PropTypes.func.isRequired,


};

MainMenu.defaultProps = {

  urlDirectory1: null,
  urlDirectory2: null,
  urlDirectory3: null,

  // footerCardGameCommunityRenewalList: null,
  // footerCardGameCommunityAccessList: null,
  // footerCardUserCommunityAccessList: null

};
