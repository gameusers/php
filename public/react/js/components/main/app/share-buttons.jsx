// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
// import { FormGroup, FormControl } from 'react-bootstrap';
import { List } from 'immutable';
// import Masonry from 'react-masonry-component';
import { Model } from '../../../models/model';
// import MainMenu from '../menu';

// import ContainerContent from '../../../../../dev/blog/wp-content/plugins/gameusers-share-buttons/js/containers/content';

import testOutput from '../../../../../dev/blog/wp-content/plugins/gameusers-share-buttons/js/entry-option';

// import '../../../../../dev/blog/wp-content/plugins/gameusers-share-buttons/js/test';

import '../../../../css/style.css';




export default class MainAppShareButtons extends React.Component {

  // constructor() {
  //   super();
  //
  //
  // }
  //
  //
  //
  componentDidMount() {

    // console.log('componentDidMount');

    // testOutput();

  }
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

          <strong>Main / {this.props.urlDirectory1} / {this.props.urlDirectory2} / {this.props.urlDirectory3}</strong>

          <p>Game Usersとは？2<br />
          Game Users（ゲームユーザーズ）はゲームユーザーのためのSNS・コミュニティサイトです。ゲームに興味のある人たちが集まって、交流がしやすくなるような様々な機能を用意しています。<br /><br />
          コンテンツについて<br />
          Game Usersが現在提供している基本的なコンテンツ（ページ）は、ゲームページ、コミュニティ、Wiki、プレイヤーの4つです。<br /><br /><br /><br />

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

          {/* <div className="game-users-share-buttons-option" id="gameusers-share-buttons-option">ABC</div> */}
          {/* <script type="text/javascript" src="https://localhost/gameusers/public/dev/blog/wp-content/plugins/gameusers-share-buttons/js/option-bundle.min.js?ver=1.0.0" /> */}

        </article>
      );

    }



    // --------------------------------------------------
    //   スマートフォン・タブレット
    // --------------------------------------------------

    return (
      <article className="content">

        <strong>Main / {this.props.urlDirectory1} / {this.props.urlDirectory2} / {this.props.urlDirectory3}</strong>

        <p>
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
  urlDirectory3: PropTypes.string,

  urlBase: PropTypes.string.isRequired,


  // --------------------------------------------------
  //   フッター
  // --------------------------------------------------

  // footerCardType: PropTypes.string.isRequired,
  // footerCardGameCommunityRenewalList: PropTypes.instanceOf(List),
  // footerCardGameCommunityAccessList: PropTypes.instanceOf(List),
  // footerCardUserCommunityAccessList: PropTypes.instanceOf(List),


  // --------------------------------------------------
  //   ドロワーメニュー / スマートフォン・タブレット用
  // --------------------------------------------------

  // drawerMenuActive: PropTypes.bool.isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  // funcUrlDirectory: PropTypes.func.isRequired,
  //
  // funcSelectFooterCardType: PropTypes.func.isRequired,
  // funcDrawerMenuActive: PropTypes.func.isRequired,


};

MainAppShareButtons.defaultProps = {

  urlDirectory1: null,
  urlDirectory2: null,
  urlDirectory3: null,

  // footerCardGameCommunityRenewalList: null,
  // footerCardGameCommunityAccessList: null,
  // footerCardUserCommunityAccessList: null

};
