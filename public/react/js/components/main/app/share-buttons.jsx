// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
// import { FormGroup, FormControl } from 'react-bootstrap';
import { List } from 'immutable';
// import Masonry from 'react-masonry-component';
import { Model } from '../../../models/model';
import MainMenu from '../menu';

import '../../../../css/style.css';



export default class MainAppShareButtons extends React.Component {

  // constructor() {
  //   super();
  //
  //   this.msnry = null;
  // }
  //
  //
  //
  // componentDidMount() {
  //
  //   console.log('componentDidMount');
  //
  //
  //
  //   // --------------------------------------------------
  //   //   スライドメニュー / スマートフォン・タブレット用
  //   // --------------------------------------------------
  //
  //   // this.props.funcSlideMenu(this.props.stateModel, true);
  //
  //
  // }
  //
  //
  // componentWillUnmount() {
  //
  //   console.log('componentWillUnmount');
  //
  //
  //
  //   // --------------------------------------------------
  //   //   スライドメニュー / スマートフォン・タブレット用
  //   // --------------------------------------------------
  //
  //   // this.props.funcSlideMenu(this.props.stateModel, false);
  //
  //
  // }

  //
  // componentDidUpdate() {
  //
  //   console.log('componentDidUpdate');
  //
  //   // imagesLoaded('footer', () => {
  //   //   this.msnry.reloadItems();
  //   //   this.msnry.layout();
  //   // });
  //
  // }


  render() {


    // --------------------------------------------------
    //   PC
    // --------------------------------------------------

    if (this.props.deviceType === 'other') {

      return (
        <article className="content">

          <strong>Main / App / Share Buttons</strong>

          <p>Game Usersとは？2<br />
          Game Users（ゲームユーザーズ）はゲームユーザーのためのSNS・コミュニティサイトです。ゲームに興味のある人たちが集まって、交流がしやすくなるような様々な機能を用意しています。<br /><br />
          コンテンツについて<br />
          Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。</p>

        </article>
      );

    }

    // if (this.props.deviceType === 'other') {
    //
    //   return (
    //     <main className="main">
    //
    //       <MainMenu {...this.props} />
    //
    //       <article className="content">
    //
    //         <strong>Main / App / Share Buttons</strong>
    //
    //         <p>Game Usersとは？2<br />
    //         Game Users（ゲームユーザーズ）はゲームユーザーのためのSNS・コミュニティサイトです。ゲームに興味のある人たちが集まって、交流がしやすくなるような様々な機能を用意しています。<br /><br />
    //         コンテンツについて<br />
    //         Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。</p>
    //
    //       </article>
    //
    //     </main>
    //   );
    //
    // }


    // --------------------------------------------------
    //   スマートフォン・タブレット
    // --------------------------------------------------

    return (
      <article className="content">

        <strong>Main / App / Share Buttons</strong>

        <p>{this.props.urlDirectory1} / {this.props.urlDirectory2}<br /><br />
        Game Usersとは？2<br />
        Game Users（ゲームユーザーズ）はゲームユーザーのためのSNS・コミュニティサイトです。ゲームに興味のある人たちが集まって、交流がしやすくなるような様々な機能を用意しています。<br /><br /><br /><br />
          <a href="https://gameusers.org/dev/blog/">リンク</a><br /><br /><br /><br />
        コンテンツについて<br />
        Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。<br /><br />
        コンテンツについて<br />
        Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。<br /><br />
        コンテンツについて<br />
        Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。<br /><br />
        コンテンツについて<br />
        Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。<br /><br />
        コンテンツについて<br />
        Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。<br /><br />
        コンテンツについて<br />
        Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。</p>

      </article>
    );


    // return (
    //   <main className="main-s">
    //
    //     <div className="wrapper-s">
    //
    //       <MainMenu {...this.props} />
    //
    //       <article className="content">
    //
    //         <strong>Main / App / Share Buttons</strong>
    //
    //         <p>{this.props.urlDirectory1} / {this.props.urlDirectory2}<br /><br />
    //         Game Usersとは？2<br />
    //         Game Users（ゲームユーザーズ）はゲームユーザーのためのSNS・コミュニティサイトです。ゲームに興味のある人たちが集まって、交流がしやすくなるような様々な機能を用意しています。<br /><br /><br /><br />
    //           <a href="https://gameusers.org/dev/blog/">リンク</a><br /><br /><br /><br />
    //         コンテンツについて<br />
    //         Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。<br /><br />
    //         コンテンツについて<br />
    //         Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。<br /><br />
    //         コンテンツについて<br />
    //         Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。<br /><br />
    //         コンテンツについて<br />
    //         Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。<br /><br />
    //         コンテンツについて<br />
    //         Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。<br /><br />
    //         コンテンツについて<br />
    //         Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。</p>
    //
    //       </article>
    //
    //       <div className="menu-s">
    //
    //         <div className="slide">
    //
    //           <div className="icon-box">
    //             <div className="icon"><span className="glyphicon glyphicon-triangle-top icon_arrow" aria-hidden="true" /></div>
    //             <div
    //               className="icon"
    //               onClick={() => this.props.funcDrawerMenuActive()}
    //               role="button"
    //               tabIndex="0"
    //             >
    //               <span className="glyphicon glyphicon-list-alt icon_menu" aria-hidden="true" />
    //             </div>
    //             <div className="icon"><span className="glyphicon glyphicon-triangle-bottom icon_arrow" aria-hidden="true" /></div>
    //           </div>
    //
    //         </div>
    //
    //       </div>
    //
    //     </div>
    //
    //   </main>
    // );


  }

}

MainAppShareButtons.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  stateModel: PropTypes.instanceOf(Model).isRequired,

  deviceType: PropTypes.string.isRequired,
  urlDirectory1: PropTypes.string,
  urlDirectory2: PropTypes.string,

  urlBase: PropTypes.string.isRequired,


  // --------------------------------------------------
  //   フッター
  // --------------------------------------------------

  footerCardType: PropTypes.string.isRequired,
  footerCardGameCommunityRenewalList: PropTypes.instanceOf(List),
  footerCardGameCommunityAccessList: PropTypes.instanceOf(List),
  footerCardUserCommunityAccessList: PropTypes.instanceOf(List),


  // --------------------------------------------------
  //   ドロワーメニュー / スマートフォン・タブレット用
  // --------------------------------------------------

  drawerMenuActive: PropTypes.bool.isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcUrlDirectory: PropTypes.func.isRequired,

  funcSelectFooterCardType: PropTypes.func.isRequired,
  funcDrawerMenuActive: PropTypes.func.isRequired,


};

MainAppShareButtons.defaultProps = {

  urlDirectory1: null,
  urlDirectory2: null,

  footerCardGameCommunityRenewalList: null,
  footerCardGameCommunityAccessList: null,
  footerCardUserCommunityAccessList: null

};
