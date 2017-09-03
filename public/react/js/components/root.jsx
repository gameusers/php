// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import { BrowserRouter, Route } from 'react-router-dom';
import ReactGA from 'react-ga';
import { ContextMenu, MenuItem, ContextMenuTrigger } from 'react-contextmenu';
import Header from './header';
import Footer from './footer';
import { Model } from '../models/model';



const About = () => (
  <div>
    <h2>Main / Share Buttons</h2>
    <p>Game Usersとは？<br />
Game Users（ゲームユーザーズ）はゲームユーザーのためのSNS・コミュニティサイトです。ゲームに興味のある人たちが集まって、交流がしやすくなるような様々な機能を用意しています。<br /><br />
コンテンツについて<br />
Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。<br /><br /></p>


    <div
      className="page-name"
      // onClick={e => this.props.funcJavascriptLink(e, pageUrl)}
      role="link"
      tabIndex="0"
    >
      <ContextMenuTrigger id={'some-unique-identifier'}>
        AAA
      </ContextMenuTrigger>
    </div>


    <ContextMenu id={'some-unique-identifier'}>
      <MenuItem>
        ContextMenu Item 1
      </MenuItem>
      <MenuItem>
        ContextMenu Item 2
      </MenuItem>
      <MenuItem divider />
      <MenuItem>
        ContextMenu Item 3
      </MenuItem>
    </ContextMenu>
  </div>
);

// const Main = () => (
//   <div>
//     <nav>
//       <Link to="/share-buttons">Main / Share Buttons</Link>
//     </nav>
//     <div>
//       <Route path="/share-buttons" component={About} />
//     </div>
//   </div>
// );

// const Footer = () => (
//   <div>
//     <h2>Footer</h2>
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


  componentWillMount() {
    // console.log('Content / componentWillMount');
    this.props.funcInitialAsynchronous(this.props.stateModel);
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

          <Route path="/app/share-buttons" component={About} />

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
  userNo: PropTypes.number,
  adBlock: PropTypes.bool.isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcInitialAsynchronous: PropTypes.func.isRequired,

};

Root.defaultProps = {

  baseName: null,
  userNo: null,

};




// export default Root;
