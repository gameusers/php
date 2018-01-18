// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import { BrowserRouter } from 'react-router-dom';
import ReactGA from 'react-ga';

import { Model } from '../models/model';
import Header from './header';
import MainMenu from './main/menu';
import MainMenuButtons from './main/menu-buttons';
import ModalNotification from './modal/notification';
import Footer from './footer';
import Contents from '../../contents/contents';

import '../../css/style.css';



export default class Root extends React.Component {

  constructor() {

    super();


    // ---------------------------------------------
    //   Google Anarytics
    // ---------------------------------------------

    ReactGA.initialize('UA-65903811-1');
    // if (process.env.NODE_ENV === 'production') {
    //   ReactGA.initialize('UA-65903811-1');
    // } else {
    //   ReactGA.initialize('UA-65903811-1', {
    //     debug: true,
    //   });
    // }

  }


  // --------------------------------------------------
  //   Lifecycle Methods
  // --------------------------------------------------

  // componentWillMount() {
  //   // console.log('Content / componentWillMount');
  //   this.props.funcInitialAsynchronous(this.props.stateModel);
  // }


  componentDidUpdate() {


    // --------------------------------------------------
    //   タイトル変更
    // --------------------------------------------------

    const title =
      this.props.stateModel.getIn(['metaMap', 'ja', this.props.urlDirectory1, this.props.urlDirectory2, this.props.urlDirectory3, 'title']) ||
      this.props.stateModel.getIn(['metaMap', 'ja', this.props.urlDirectory1, this.props.urlDirectory2, 'title']) ||
      this.props.stateModel.getIn(['metaMap', 'ja', this.props.urlDirectory1, 'title']);

    // console.log('title = ', title);
    document.title = title;


    // --------------------------------------------------
    //   Meta 変更
    // --------------------------------------------------

    const keywords =
      this.props.stateModel.getIn(['metaMap', 'ja', this.props.urlDirectory1, this.props.urlDirectory2, this.props.urlDirectory3, 'keywords']) ||
      this.props.stateModel.getIn(['metaMap', 'ja', this.props.urlDirectory1, this.props.urlDirectory2, 'keywords']) ||
      this.props.stateModel.getIn(['metaMap', 'ja', this.props.urlDirectory1, 'keywords']);

    const description =
      this.props.stateModel.getIn(['metaMap', 'ja', this.props.urlDirectory1, this.props.urlDirectory2, this.props.urlDirectory3, 'description']) ||
      this.props.stateModel.getIn(['metaMap', 'ja', this.props.urlDirectory1, this.props.urlDirectory2, 'description']) ||
      this.props.stateModel.getIn(['metaMap', 'ja', this.props.urlDirectory1, 'description']);

    const ogType =
      this.props.stateModel.getIn(['metaMap', 'ja', this.props.urlDirectory1, this.props.urlDirectory2, this.props.urlDirectory3, 'ogType']) ||
      this.props.stateModel.getIn(['metaMap', 'ja', this.props.urlDirectory1, this.props.urlDirectory2, 'ogType']) ||
      this.props.stateModel.getIn(['metaMap', 'ja', this.props.urlDirectory1, 'ogType']);


    const metaArr = document.head.children;
    const metaLength = metaArr.length;

    for (let i = 0; i < metaLength; i += 1) {

      const name = metaArr[i].getAttribute('name');
      const property = metaArr[i].getAttribute('property');
      // console.log('name = ', name);
      // console.log('property = ', property);


      if (name === 'keywords') {
        const dis = metaArr[i];
        dis.setAttribute('content', keywords);
      }

      if (name === 'description') {
        const dis = metaArr[i];
        dis.setAttribute('content', description);
      }

      if (property === 'og:title') {
        const dis = metaArr[i];
        dis.setAttribute('content', title);
      }

      if (property === 'og:description') {
        const dis = metaArr[i];
        dis.setAttribute('content', description);
      }

      if (property === 'og:type') {
        const dis = metaArr[i];
        dis.setAttribute('content', ogType);
      }

      if (property === 'og:url') {
        const dis = metaArr[i];
        dis.setAttribute('content', window.location.href);
      }

    }

    // console.log(window.location.href);

  }



  /**
   * Google Anarytics のコードを追加する
   * 本番環境のみ表示
   * @return
   */
  googleAnalytics() {

    if (process.env.NODE_ENV === 'production') {
      ReactGA.set({ page: window.location.pathname });

      if (this.props.userNo) {
        ReactGA.set({ userId: `UserNo_${this.props.userNo}` });
        ReactGA.set({ dimension1: 'Login' });
      } else {
        ReactGA.set({ dimension1: 'Not_Login' });
      }

      if (this.props.adBlock) {
        ReactGA.set({ dimension2: 'Administrator' });
      }

      ReactGA.pageview(window.location.pathname);
    }

  }



  render() {
    return (
      <BrowserRouter basename={this.props.baseName} onUpdate={this.googleAnalytics()}>
        <div>

          <Header {...this.props} />

          <main className={this.props.deviceType === 'other' ? 'main' : 'main-s'}>

            <MainMenu {...this.props} />

            <Contents {...this.props} />

            {this.props.deviceType !== 'other' &&
              <div className="share-buttons" id="game-users-share-buttons-official">
                {/* <div id="game-users-share-buttons" data-theme="gameusers1-m2a4oi43" /> */}
                <div data-game-users-share-buttons="gameusers1-m2a4oi43" />
              </div>
            }

            <ModalNotification {...this.props} />

            <MainMenuButtons {...this.props} />

          </main>

          <Footer {...this.props} />

        </div>
      </BrowserRouter>
    );
  }

}


// --------------------------------------------------
//   PropTypes
// --------------------------------------------------

Root.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  stateModel: PropTypes.instanceOf(Model).isRequired,

  urlDirectory1: PropTypes.string,
  urlDirectory2: PropTypes.string,
  urlDirectory3: PropTypes.string,

  baseName: PropTypes.string,

  deviceType: PropTypes.string.isRequired,
  userNo: PropTypes.number,
  adBlock: PropTypes.bool.isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcInitialAsynchronous: PropTypes.func.isRequired,
  funcMenuDrawerActive: PropTypes.func.isRequired,


};

Root.defaultProps = {

  urlDirectory1: null,
  urlDirectory2: null,
  urlDirectory3: null,

  baseName: null,
  userNo: null,

};




// export default Root;
