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

import ContainerContentsApp from '../../contents/app/js/containers/app';

// import ContentsAppShareButtons from '../../contents/app/js/components/share-buttons';
// import ContentsAppPay from '../../contents/app/js/components/pay';

// import MainAppShareButtons from './main/app/share-buttons';
// import MainAppPay from './main/app/pay';

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


  // componentWillMount() {
  //   // console.log('Content / componentWillMount');
  //   this.props.funcInitialAsynchronous(this.props.stateModel);
  // }



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

            <ContainerContentsApp {...this.props} />

            {/* <Route exact path="/app/share-buttons" render={() => <ContainerContentsApp {...this.props} />} /> */}
            {/* <div>
              <Route exact path="/app/share-buttons" render={() => <ContentsAppShareButtons {...this.props} />} />
              <Route exact path="/app/pay" render={() => <ContentsAppPay {...this.props} />} />
            </div> */}
            {/* <Route exact path="/app/pay" render={() => <ContainerContentsApp {...this.props} />} /> */}

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

  baseName: null,
  userNo: null,

};




// export default Root;
