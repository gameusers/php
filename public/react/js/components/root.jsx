// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import { BrowserRouter, Route } from 'react-router-dom';
import ReactGA from 'react-ga';

import { Model } from '../models/model';
import Header from './header';
import MainMenu from './main/menu';
import MainMenuButtons from './main/menu-buttons';
import MainAppShareButtons from './main/app/share-buttons';
import ModalNotification from './modal/notification';
import Footer from './footer';

import '../../css/style.css';


// const Test = () => (
//   <div>
//     <h2>Test</h2>
//   </div>
// );



export default class Root extends React.Component {

  constructor() {

    super();


    // ---------------------------------------------
    //   Google Anarytics
    // ---------------------------------------------

    if (process.env.NODE_ENV === 'production') {
      ReactGA.initialize('UA-65903811-1');
    } else {
      ReactGA.initialize('UA-65903811-1', {
        debug: false,
      });
    }

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

            <Route exact path="/app/share-buttons" render={() => <MainAppShareButtons {...this.props} />} />
            <Route exact path="/app/share-buttons/test1" render={() => <MainAppShareButtons {...this.props} />} />

            <ModalNotification {...this.props} />

            <MainMenuButtons {...this.props} />

          </main>

          <Footer {...this.props} />

        </div>
      </BrowserRouter>
    );
  }

  // render() {
  //   return (
  //     <BrowserRouter basename={this.props.baseName} onUpdate={this.googleAnalytics()}>
  //       <div>
  //
  //         <Header {...this.props} />
  //
  //         <Route path="/app/share-buttons" render={() => <MainAppShareButtons {...this.props} />} />
  //         <Route path="/app/test1" render={() => <MainAppShareButtons {...this.props} />} />
  //
  //         <Footer {...this.props} />
  //
  //       </div>
  //     </BrowserRouter>
  //   );
  // }

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
