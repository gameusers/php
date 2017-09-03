import React from 'react';
import { Link } from 'react-router-dom';

import { getDeviceType, formatDate } from '../modules/common';

class Header extends React.Component {
  render() {
    console.log(location.href);
    let codePlayer = null;
    let codeLogin = null;
    let codeLogout = null;

    // --------------------------------------------------
    //   ログインしている場合
    // --------------------------------------------------
    if (this.props.userNo && this.props.playerId) {
      codePlayer = <li><a href={`${this.props.urlBase}pl/${this.props.playerId}`}><span className="glyphicon glyphicon-user" aria-hidden="true" /> プレイヤー{this.props.match.params.segment}</a></li>;
      codeLogout = <li><a href={`${this.props.urlBase}logout`}><span className="glyphicon glyphicon-log-out" aria-hidden="true" /> ログアウト</a></li>;

    // --------------------------------------------------
    //   ログインしていない場合
    // --------------------------------------------------
    } else {
      codeLogin = <li><a href={`${this.props.urlBase}login`}><span className="glyphicon glyphicon-log-out" aria-hidden="true" /> ログイン</a></li>;
    }

    return (
      <div>
        <header className="cd-auto-hide-header">

          <div className="logo">
            <a href={this.props.urlBase}><img src="assets/img/common/gameusers_logo.png" alt="Game Users" /></a>
            <div className="bell_box" id="header_notifications" data-user_no={this.props.userNo}>
              <div className="bell"><span className="glyphicon glyphicon-bell" aria-hidden="true" /></div>
              <div className="bell_number"><span className="badge" id="header_notifications_unread_total" data-unread_id="">-</span></div>
            </div>
          </div>

          <nav className="cd-primary-nav">
            <a href="#cd-navigation" className="nav-trigger">
              <span>
                <em aria-hidden="true" />
                Menu
              </span>
            </a>

            <ul id="cd-navigation">
              {codePlayer}
              <li><a href={`${this.props.urlBase}help`}><span className="glyphicon glyphicon-question-sign" aria-hidden="true" /> ヘルプ</a></li>
              {codeLogin}
              {codeLogout}
            </ul>
          </nav>

        </header>

        <HeroImage {...this.props} />
        <Tab {...this.props} />
      </div>
    );
  }
}
Header.propTypes = {
  userNo: React.PropTypes.number,
  playerId: React.PropTypes.string,
  urlBase: React.PropTypes.string.isRequired,
};
Header.defaultProps = {
  userNo: null,
  playerId: null,
  urlBase: 'https://gameusers.org/'
};


const HeroImage = (props) => {
  const imageType = getDeviceType() === 'smartphone' ? '_s' : '';

  const heroTitleLink = props.communityId ? `${props.urlBase}uc/${props.communityId}` : `${props.urlBase}gc/${props.gameId}`;
  const heroTitle = props.communityName ? props.communityName : props.gameName;

  // console.log(process.env.NODE_ENV);

  // if (process.env.NODE_ENV === 'development') {
  //   console.log('aaa');
  // }
  //
  // console.log(`NODE_ENV ${process.env.NODE_ENV}`);

  // --------------------------------------------------
  //   データ作成 / ゲームについての情報（ハード・ジャンルなど）
  // --------------------------------------------------
  const dataTitle = props.gameSubtitle ? `${heroTitle} ${props.gameSubtitle}` : heroTitle;
  let tempArr = [];

  // ---------------------------------------------
  //   ハードウェア
  // ---------------------------------------------
  let dataHardware = null;

  if (props.hardwareObj) {
    tempArr = [];
    Object.keys(props.hardwareObj).forEach((key) => {
      tempArr.push(props.hardwareObj[key].abbreviation);
    });
    dataHardware = props.hardwareObj.length > 1 ? tempArr.join(', ') : props.hardwareObj[0].name;
  }

  // ---------------------------------------------
  //   ジャンル
  // ---------------------------------------------
  let dataGenre = null;

  if (props.genreObj) {
    tempArr = [];
    Object.keys(props.genreObj).forEach((key) => {
      tempArr.push(props.genreObj[key].name);
    });
    dataGenre = tempArr.join(', ');
  }

  // ---------------------------------------------
  //   プレイ人数
  // ---------------------------------------------
  let dataPlayersMax = null;

  if (props.playersMax === '1') {
    dataPlayersMax = '1人';
  } else if (props.playersMax > 1) {
    dataPlayersMax = `1-${props.playersMax}人`;
  }

  // ---------------------------------------------
  //   発売日 / 一番古い日付を表示する
  // ---------------------------------------------
  let dataReleaseDate = null;

  if (props.releaseDate1) {
    const releaseDate1 = props.releaseDate1 ? new Date(props.releaseDate1) : null;
    const releaseDate2 = props.releaseDate2 ? new Date(props.releaseDate2) : null;
    const releaseDate3 = props.releaseDate3 ? new Date(props.releaseDate3) : null;
    const releaseDate4 = props.releaseDate4 ? new Date(props.releaseDate4) : null;
    const releaseDate5 = props.releaseDate5 ? new Date(props.releaseDate5) : null;

    dataReleaseDate = releaseDate1;
    if (releaseDate2 && dataReleaseDate.getTime() > releaseDate2.getTime()) dataReleaseDate = releaseDate2;
    if (releaseDate3 && dataReleaseDate.getTime() > releaseDate3.getTime()) dataReleaseDate = releaseDate3;
    if (releaseDate4 && dataReleaseDate.getTime() > releaseDate4.getTime()) dataReleaseDate = releaseDate4;
    if (releaseDate5 && dataReleaseDate.getTime() > releaseDate5.getTime()) dataReleaseDate = releaseDate5;

    dataReleaseDate = formatDate(dataReleaseDate);
  }

  // ---------------------------------------------
  //   開発
  // ---------------------------------------------
  let dataDeveloper = null;

  if (props.developerObj) {
    tempArr = [];
    Object.keys(props.developerObj).forEach((key) => {
      tempArr.push(props.developerObj[key].name);
    });
    dataDeveloper = tempArr.join(', ');
  }

  // ---------------------------------------------
  //   リンク / 小さいアイコンでリンクを表示する
  // ---------------------------------------------
  const dataLink = [];
  let temp = null;

  if (props.linkObj) {
    Object.keys(props.linkObj).forEach((key) => {
      if (props.linkObj[key].type === 'Official') {
        dataLink.push(
          <span key={key} className="icon">
            <a href={props.linkObj[key].url}>
              <button type="button" className="btn btn-danger btn-xs">公式</button>
            </a>
          </span>
        );
      } else if (props.linkObj[key].type === 'Twitter') {
        temp = `${props.urlBase}assets/img/common/twitter@2x.png`;
        dataLink.push(
          <span key={key} className="icon">
            <a href={props.linkObj[key].url}>
              <img alt="Twitter Link" src={temp} width="20" height="20" />
            </a>
          </span>
        );
      } else if (props.linkObj[key].type === 'Facebook') {
        temp = `${props.urlBase}assets/img/common/facebook@2x.png`;
        dataLink.push(
          <span key={key} className="icon">
            <a href={props.linkObj[key].url}>
              <img alt="Facebook Link" src={temp} width="20" height="20" />
            </a>
          </span>
        );
      } else if (props.linkObj[key].type === 'YouTube') {
        temp = `${props.urlBase}assets/img/common/youtube_alt@2x.png`;
        dataLink.push(
          <span key={key} className="icon">
            <a href={props.linkObj[key].url}>
              <img alt="YouTube Link" src={temp} width="20" height="20" />
            </a>
          </span>
        );
      } else if (props.linkObj[key].type === 'Steam') {
        temp = `${props.urlBase}assets/img/common/stream@2x.png`;
        dataLink.push(
          <span key={key} className="icon">
            <a href={props.linkObj[key].url}>
              <img alt="Steam Link" src={temp} width="20" height="20" />
            </a>
          </span>
        );
      } else if (props.linkObj[key].type === 'etc') {
        temp = `${props.urlBase}assets/img/common/facebook@2x.png`;
        dataLink.push(
          <span key={key} className="icon">
            <a href={props.linkObj[key].url}>
              <button type="button" className="btn btn-danger btn-xs">{props.linkObj[key].name}</button>
            </a>
          </span>
        );
      }
    });
  }


  let codeData = null;

  if (!props.communityName) {
    codeData = (
      <div className="hero_image_data">
        <div className="title">{dataTitle}</div>
        <p className="data">
          ハード | {dataHardware}<br />
          ジャンル | {dataGenre}<br />
          プレイ人数 | {dataPlayersMax}<br />
          発売日 | {dataReleaseDate}<br />
          開発 | {dataDeveloper}<br />
        </p>
        <div className="link">{dataLink}</div>
      </div>
    );
  }



  // --------------------------------------------------
  //   ヒーローイメージがある場合 / 大きい画像とゲーム情報
  // --------------------------------------------------
  let code = null;

  if (props.imageId) {
    const dateParse = Date.parse(props.renewalDate);

    const styleBackgroundImage = {
      background: `url(${props.urlBase}assets/img/u/${props.imageId}${imageType}.jpg?${dateParse})`,
      backgroundRepeat: 'no-repeat',
      backgroundPosition: 'center',
      backgroundSize: 'cover',
    };

    const codeDataRight = codeData ? <div className="hero_image_data_right">{codeData}</div> : null;

    code = (
      <section className="cd-hero">
        <div className="cd-hero-content" id="hero_image" style={styleBackgroundImage}>
          <div className="hero_image_box">
            <a href={heroTitleLink} className="hero_title_link"><h1 className="hero_title">{heroTitle}</h1></a>
            {codeDataRight}
          </div>
        </div>
      </section>
    );

  // --------------------------------------------------
  //   ヒーローイメージがない場合 / 小さいサムネイルとゲーム情報
  // --------------------------------------------------
  } else {
    const styleBackgroundImage = {
      background: `url(${props.urlBase}assets/img/common/header_back.jpg)`,
      backgroundRepeat: 'no-repeat',
      backgroundPosition: 'center',
      backgroundSize: 'cover',
    };

    let thumbnailUrl = '';

    if (props.gameThumbnail) {
      thumbnailUrl = `${props.urlBase}assets/img/game/${props.gameNo}/thumbnail.jpg`;
    } else {
      const dateThumbnailNone = new Date(props.gameRenewalDate);
      const thumbnailNoneSecond = dateThumbnailNone.getSeconds();
      thumbnailUrl = `${props.urlBase}assets/img/common/thumbnail_none_${thumbnailNoneSecond}.png`;
    }

    code = (
      <section className="cd-hero-s">
        <div className="cd-hero-content" id="hero_image" style={styleBackgroundImage}>
          <div className="card_hero_box">
            <div className="card_hero">
              <a href={heroTitleLink} className="card_link">
                <div className="image"><img alt={props.gameName} src={thumbnailUrl} /></div>
              </a>
            </div>
            {codeData}
          </div>
        </div>
      </section>
    );
  }

  return (
    code
  );
};
HeroImage.propTypes = {
  urlBase: React.PropTypes.string.isRequired,
  imageId: React.PropTypes.string,
  renewalDate: React.PropTypes.string,
  communityId: React.PropTypes.string,
  communityName: React.PropTypes.string,
  gameId: React.PropTypes.string,
  gameName: React.PropTypes.string,
  gameThumbnail: React.PropTypes.string,
  hardwareObj: React.PropTypes.arrayOf(
    React.PropTypes.shape({
      name: React.PropTypes.string.isRequired,
      abbreviation: React.PropTypes.string,
    })
  ),
  genreObj: React.PropTypes.arrayOf(
    React.PropTypes.shape({
      name: React.PropTypes.string.isRequired,
    })
  ),
  playersMax: React.PropTypes.number,
  releaseDate1: React.PropTypes.string,
  releaseDate2: React.PropTypes.string,
  releaseDate3: React.PropTypes.string,
  releaseDate4: React.PropTypes.string,
  releaseDate5: React.PropTypes.string,
  developerObj: React.PropTypes.arrayOf(
    React.PropTypes.shape({
      name: React.PropTypes.string.isRequired,
      studio: React.PropTypes.string,
    })
  ),
  linkObj: React.PropTypes.arrayOf(
    React.PropTypes.shape({
      type: React.PropTypes.string.isRequired,
      name: React.PropTypes.string,
      url: React.PropTypes.string.isRequired,
    })
  ),
};
HeroImage.defaultProps = {
  urlBase: 'https://gameusers.org/',
  imageId: null,
  renewalDate: null,
  communityId: null,
  communityName: null,
  gameId: null,
  gameName: null,
  gameThumbnail: null,
  hardwareObj: null,
  genreObj: null,
  playersMax: null,
  releaseDate1: null,
  releaseDate2: null,
  releaseDate3: null,
  releaseDate4: null,
  releaseDate5: null,
  developerObj: null,
  linkObj: null,
};


const Tab = (props) => {
  // const url = `${props.urlBase}sc`;
  return (
    <ul>
      <li><Link to="/sc">Home</Link></li>
      <li><Link to="/about">About</Link></li>
      <li><Link to="/topics">Topics</Link></li>
    </ul>
  );
};
Tab.propTypes = {
  urlBase: React.PropTypes.string.isRequired,
};
Tab.defaultProps = {
  urlBase: 'https://gameusers.org/',
};


export default Header;
