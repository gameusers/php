// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';
// import { Button, FormGroup, Radio } from 'react-bootstrap';
import { List, Map } from 'immutable';
import { Model } from '../models/model';
import { getDeviceType, formatDateTime } from '../modules/common';

import '../../lib/auto-hiding-navigation/auto-hiding-navigation.css';
import '../../css/style.css';





export default class Header extends React.Component {

  // componentWillReceiveProps(nextProps) {
  //   // console.log('componentWillReceiveProps / nextProps = ', nextProps);
  // }


  // validationStateRssUrl() {
  //   let state = 'error';
  //   if (!this.props.rssUrl || this.props.rssUrl.match(/^(https?)(:\/\/[-_.!~*'()a-zA-Z0-9;/?:@&=+$,%#]+)$/)) {
  //     state = 'success';
  //   }
  //   return state;
  // }


  // --------------------------------------------------
  //   最上部のメニュー / プレイヤー ヘルプ ログインなど
  // --------------------------------------------------

  codeNavigationMenu() {

    const codeArr = [];


    if (this.props.userNo) {
      codeArr.push(
        <li key="player"><a href={`${this.props.urlBase}pl/${this.props.playerId}`}><span className="glyphicon glyphicon-user" aria-hidden="true" /> プレイヤー</a></li>
      );
    }

    codeArr.push(
      <li key="help"><a href={`${this.props.urlBase}help`}><span className="glyphicon glyphicon-question-sign" aria-hidden="true" /> ヘルプ</a></li>
    );


    // --------------------------------------------------
    //   ログインしている場合
    // --------------------------------------------------

    if (this.props.userNo) {
      codeArr.push(
        <li key="logout"><a href={`${this.props.urlBase}logout`}><span className="glyphicon glyphicon-log-out" aria-hidden="true" /> ログアウト</a></li>
      );

    // --------------------------------------------------
    //   ログアウトしている場合
    // --------------------------------------------------

    } else {
      codeArr.push(
        <li key="login"><a href={`${this.props.urlBase}login`}><span className="glyphicon glyphicon-log-in" aria-hidden="true" /> ログイン</a></li>
      );
    }

    return codeArr;

  }



  // --------------------------------------------------
  //   ヒーローイメージ or 小さいサムネイル and ゲームデータ
  // --------------------------------------------------

  codeHeroImage() {


    // --------------------------------------------------
    //   タイトル
    // --------------------------------------------------

    const heroImageTitle = this.props.headerCommunityName ? this.props.headerCommunityName : this.props.headerGameName;


    // --------------------------------------------------
    //   ゲームについてのデータ（右側に半透過して表示される情報）
    //   ハード、ジャンル、公式サイトへのリンクなど
    // --------------------------------------------------

    const dataTitle = this.props.headerGameSubtitle ? `${heroImageTitle} ${this.props.headerGameSubtitle}` : heroImageTitle;
    let tempArr = [];


    // ---------------------------------------------
    //   ハードウェア
    // ---------------------------------------------

    let dataHardware = null;
    // console.log('this.props.headerGameHardwareList = ', this.props.headerGameHardwareList.toJS());
    // console.log('this.props.headerGameHardwareList.count() = ', this.props.headerGameHardwareList.count());
    if (this.props.headerGameHardwareList) {
      tempArr = [];
      this.props.headerGameHardwareList.valueSeq().forEach((value) => {
        tempArr.push(value.get('abbreviation'));
      });

      if (this.props.headerGameHardwareList.count() > 1) {
        dataHardware = tempArr.join(', ');
      } else {
        dataHardware = this.props.headerGameHardwareList.first().get('name');
      }
    }



    // ---------------------------------------------
    //   ジャンル
    // ---------------------------------------------

    let dataGenre = null;

    if (this.props.headerGameGenreList) {
      tempArr = [];
      this.props.headerGameGenreList.valueSeq().forEach((value) => {
        tempArr.push(value.get('name'));
      });
      dataGenre = tempArr.join(', ');
    }


    // ---------------------------------------------
    //   プレイ人数
    // ---------------------------------------------

    let dataPlayersMax = null;

    if (this.props.headerGamePlayersMax === '1') {
      dataPlayersMax = '1人';
    } else if (this.props.headerGamePlayersMax > 1) {
      dataPlayersMax = `1-${this.props.headerGamePlayersMax}人`;
    }


    // ---------------------------------------------
    //   発売日 / 一番古い日付を表示する
    // ---------------------------------------------

    let dataReleaseDate = null;

    if (this.props.headerGameReleaseDate1) {
      const releaseDate1 = this.props.headerGameReleaseDate1 ? new Date(this.props.headerGameReleaseDate1) : null;
      const releaseDate2 = this.props.headerGameReleaseDate2 ? new Date(this.props.headerGameReleaseDate2) : null;
      const releaseDate3 = this.props.headerGameReleaseDate3 ? new Date(this.props.headerGameReleaseDate3) : null;
      const releaseDate4 = this.props.headerGameReleaseDate4 ? new Date(this.props.headerGameReleaseDate4) : null;
      const releaseDate5 = this.props.headerGameReleaseDate5 ? new Date(this.props.headerGameReleaseDate5) : null;

      dataReleaseDate = releaseDate1;
      if (releaseDate2 && dataReleaseDate.getTime() > releaseDate2.getTime()) dataReleaseDate = releaseDate2;
      if (releaseDate3 && dataReleaseDate.getTime() > releaseDate3.getTime()) dataReleaseDate = releaseDate3;
      if (releaseDate4 && dataReleaseDate.getTime() > releaseDate4.getTime()) dataReleaseDate = releaseDate4;
      if (releaseDate5 && dataReleaseDate.getTime() > releaseDate5.getTime()) dataReleaseDate = releaseDate5;

      dataReleaseDate = formatDateTime(dataReleaseDate);
    }


    // ---------------------------------------------
    //   開発
    // ---------------------------------------------

    let dataDeveloper = null;

    if (this.props.headerGameDeveloperList) {
      tempArr = [];
      this.props.headerGameDeveloperList.valueSeq().forEach((value) => {
        tempArr.push(value.get('name'));
      });
      dataDeveloper = tempArr.join(', ');
    }


    // ---------------------------------------------
    //   リンク / 小さいアイコンでリンクを表示する
    // ---------------------------------------------

    const dataLinkArr = [];

    if (this.props.headerGameLinkList) {

      this.props.headerGameLinkList.entrySeq().forEach((e) => {

        const key = e[0];
        const value = e[1];
        let temp = null;

        if (value.get('type') === 'Official') {
          temp = <button type="button" className="btn btn-danger btn-xs">公式</button>;
        } else if (value.get('type') === 'Twitter') {
          temp = <img alt="Twitter Link" src={`${this.props.urlBase}react/img/common/twitter@2x.png`} width="20" height="20" />;
        } else if (value.get('type') === 'Facebook') {
          temp = <img alt="Facebook Link" src={`${this.props.urlBase}react/img/common/facebook@2x.png`} width="20" height="20" />;
        } else if (value.get('type') === 'YouTube') {
          temp = <img alt="YouTube Link" src={`${this.props.urlBase}react/img/common/youtube@2x.png`} width="20" height="20" />;
        } else if (value.get('type') === 'Steam') {
          temp = <img alt="Steam Link" src={`${this.props.urlBase}react/img/common/stream@2x.png`} width="20" height="20" />;
        } else if (value.get('type') === 'etc') {
          temp = <button type="button" className="btn btn-danger btn-xs">{value.get('name')}</button>;
        }

        dataLinkArr.push(
          <span key={key} className="icon">
            <a href={value.get('url')}>
              {temp}
            </a>
          </span>
        );

      });

    }


    let codeData = null;

    if (!this.props.headerCommunityId) {
      codeData = (
        <div className="hero-image-data">
          <div className="title">{dataTitle}</div>
          <p className="data">
            ハード | {dataHardware}<br />
            ジャンル | {dataGenre}<br />
            プレイ人数 | {dataPlayersMax}<br />
            発売日 | {dataReleaseDate}<br />
            開発 | {dataDeveloper}<br />
          </p>
          <div className="link">{dataLinkArr}</div>
        </div>
      );
    }



    // --------------------------------------------------
    //   ヒーローイメージがある場合
    //   大きい画像とゲーム情報
    // --------------------------------------------------

    let code = null;

    const imageType = this.props.deviceType === 'smartphone' ? '_s' : '';
    const heroImageTitleLink = this.props.headerCommunityId ? `${this.props.urlBase}uc/${this.props.headerCommunityId}` : `${this.props.urlBase}gc/${this.props.headerGameId}`;

    if (this.props.headerHeroImageId) {

      const dateParse = Date.parse(this.props.headerGameRenewalDate);

      const styleBackgroundImage = {
        background: `url(${this.props.urlBase}assets/img/u/${this.props.headerHeroImageId}${imageType}.jpg?${dateParse})`,
        backgroundRepeat: 'no-repeat',
        backgroundPosition: 'center',
        backgroundSize: 'cover',
      };

      const codeDataRight = codeData ? <div className="hero-image-data-position-right">{codeData}</div> : null;

      code = (
        <section className="cd-hero">
          <div className="cd-hero-content" style={styleBackgroundImage}>
            <div className="hero-image-box">
              <a href={heroImageTitleLink} className="title-link"><h1 className="title">{heroImageTitle}</h1></a>
              {codeDataRight}
            </div>
          </div>
        </section>
      );


    // --------------------------------------------------
    //   ヒーローイメージがない場合
    //   小さいサムネイルとゲーム情報
    // --------------------------------------------------

    } else {

      const styleBackgroundImage = {
        background: `url(${this.props.urlBase}assets/img/common/header_back.jpg)`,
        backgroundRepeat: 'no-repeat',
        backgroundPosition: 'center',
        backgroundSize: 'cover',
      };

      let thumbnailUrl = '';

      if (this.props.headerGameThumbnail) {
        thumbnailUrl = `${this.props.urlBase}assets/img/game/${this.props.headerGameNo}/thumbnail.jpg`;
      } else {
        const renewalDate = new Date(this.props.headerGameRenewalDate);
        const second = renewalDate.getSeconds();
        thumbnailUrl = `${this.props.urlBase}react/img/common/thumbnail-none-${second}.png`;
      }

      code = (
        <section className="cd-hero-s">
          <div className="cd-hero-content" style={styleBackgroundImage}>
            <div className="card-hero-box">
              <div className="card">
                <a href={heroImageTitleLink} className="card-link">
                  <div className="image"><img alt={this.props.headerGameName} src={thumbnailUrl} /></div>
                </a>
              </div>
              {codeData}
            </div>
          </div>
        </section>
      );

    }

    return code;

  }



  // --------------------------------------------------
  //   タブ / ヘッダー最下部の紺色の帯
  //   className={this.props.urlDirectory2 === 'share-buttons' && 'active'}
  //   この書き方は分岐の簡略表記、条件に当てはまる際に active を表示させる
  //   参考：http://qiita.com/endam/items/1bde821c4b29f9b663da
  // --------------------------------------------------

  codeMenuBottom() {

    let map = Map();

    if (this.props.headerMenuMap.getIn([this.props.urlDirectory1])) {
      map = this.props.headerMenuMap.getIn([this.props.urlDirectory1]);
    }


    const codeArr = [];

    map.entrySeq().forEach((e) => {

      const key = e[0];
      const value = e[1];


      // console.log('value.get("activeUrlDirectory3") = ', value.get('activeUrlDirectory3'));


      // --------------------------------------------------
      //   リンク
      // --------------------------------------------------

      let linkTo = '';
      if (value.get('urlDirectory1')) linkTo += `/${value.get('urlDirectory1')}`;
      if (value.get('urlDirectory2')) linkTo += `/${value.get('urlDirectory2')}`;
      if (value.get('activeUrlDirectory3')) linkTo += `/${value.get('activeUrlDirectory3')}`;


      codeArr.push(
        <li className={value.get('urlDirectory2') === this.props.urlDirectory2 && 'active'} key={key}>
          <Link
            to={linkTo}
            onClick={() => this.props.funcUrlDirectory(value.get('urlDirectory1'), value.get('urlDirectory2'), value.get('activeUrlDirectory3'))}
          >
            {value.get('text')}
          </Link>
        </li>
      );

    });

    return codeArr;

  }



  render() {

    return (
      <header>
        <div className="cd-auto-hide-header">

          <div className="logo">
            <a href={this.props.urlBase}>
              <img src={`${this.props.urlBase}react/img/common/gameusers-logo.png`} alt="Game Users" />
            </a>

            {this.props.userNo &&
              <div
                className="bell-box"
                onClick={() => this.props.funcShowModalNotification(this.props.stateModel)}
                role="menuitem"
                tabIndex="0"
              >
                <div className="bell"><span className="glyphicon glyphicon-bell" aria-hidden="true" /></div>
                <div className="bell-number"><span className="badge" id="header_notifications_unread_total" data-unread_id="">{this.props.notificationUnreadCount}</span></div>
              </div>
            }

          </div>

          <nav className="cd-primary-nav">
            <a href="#cd-navigation" className="nav-trigger">
              <span>
                <em aria-hidden="true" />
                Menu
              </span>
            </a>

            <ul id="cd-navigation">
              {this.codeNavigationMenu()}
            </ul>
          </nav>

        </div>

        {this.codeHeroImage()}

        <nav className="cd-secondary-nav">
          <ul>
            {this.codeMenuBottom()}
          </ul>
        </nav>

      </header>
    );
  }

}

Header.propTypes = {


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  stateModel: PropTypes.instanceOf(Model).isRequired,

  urlDirectory1: PropTypes.string,
  urlDirectory2: PropTypes.string,
  // urlDirectory3: PropTypes.string,

  deviceType: PropTypes.string.isRequired,
  urlBase: PropTypes.string.isRequired,
  userNo: PropTypes.number,
  playerId: PropTypes.string,


  // --------------------------------------------------
  //   通知
  // --------------------------------------------------

  notificationUnreadCount: PropTypes.number.isRequired,


  // --------------------------------------------------
  //   ヘッダー
  // --------------------------------------------------

  headerHeroImageId: PropTypes.string,
  // headerHeroImageRenewalDate: PropTypes.string,

  // headerCommunityNo: PropTypes.number,
  // headerCommunityRenewalDate: PropTypes.string,
  headerCommunityId: PropTypes.string,
  headerCommunityName: PropTypes.string,

  headerGameNo: PropTypes.number,
  headerGameRenewalDate: PropTypes.string,
  headerGameId: PropTypes.string,
  headerGameName: PropTypes.string,
  headerGameSubtitle: PropTypes.string,
  headerGameThumbnail: PropTypes.number,
  headerGameReleaseDate1: PropTypes.string,
  headerGameReleaseDate2: PropTypes.string,
  headerGameReleaseDate3: PropTypes.string,
  headerGameReleaseDate4: PropTypes.string,
  headerGameReleaseDate5: PropTypes.string,
  headerGamePlayersMax: PropTypes.number,
  headerGameHardwareList: PropTypes.instanceOf(List),
  headerGameGenreList: PropTypes.instanceOf(List),
  headerGameDeveloperList: PropTypes.instanceOf(List),
  headerGameLinkList: PropTypes.instanceOf(List),

  headerMenuMap: PropTypes.instanceOf(Map).isRequired,


  // --------------------------------------------------
  //   関数
  // --------------------------------------------------

  funcUrlDirectory: PropTypes.func.isRequired,

  funcShowModalNotification: PropTypes.func.isRequired


};

Header.defaultProps = {

  urlDirectory1: null,
  urlDirectory2: null,
  // urlDirectory3: null,

  // urlBase: null,
  userNo: null,
  playerId: null,



  headerHeroImageId: null,
  // headerHeroImageRenewalDate: null,

  // headerCommunityNo: null,
  // headerCommunityRenewalDate: null,
  headerCommunityId: null,
  headerCommunityName: null,

  headerGameNo: null,
  headerGameRenewalDate: null,
  headerGameId: null,
  headerGameName: null,
  headerGameSubtitle: null,
  headerGameThumbnail: null,
  headerGameReleaseDate1: null,
  headerGameReleaseDate2: null,
  headerGameReleaseDate3: null,
  headerGameReleaseDate4: null,
  headerGameReleaseDate5: null,
  headerGamePlayersMax: null,
  headerGameHardwareList: null,
  headerGameGenreList: null,
  headerGameDeveloperList: null,
  headerGameLinkList: null

};
