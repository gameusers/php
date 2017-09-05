// --------------------------------------------------
//   Import
// --------------------------------------------------

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import 'whatwg-fetch';
import 'babel-polyfill';
import Cookies from 'js-cookie';
// import 'magnific-popup';
import Root from '../components/root';
import * as actions from '../actions/action';


// --------------------------------------------------
//   mapStateToProps
// --------------------------------------------------

const mapStateToProps = state => ({


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  stateModel: state,

  baseName: state.get('baseName'),
  urlDirectory1: state.get('urlDirectory1'),
  urlDirectory2: state.get('urlDirectory2'),
  urlDirectory3: state.get('urlDirectory3'),

  deviceType: state.get('deviceType'),
  deviceOs: state.get('deviceOs'),
  host: state.get('host'),
  userAgent: state.get('userAgent'),
  userNo: state.get('userNo'),
  playerId: state.get('playerId'),
  language: state.get('language'),
  urlBase: state.get('urlBase'),
  adBlock: state.get('adBlock'),
  paginationColumn: state.get('paginationColumn'),
  csrfToken: state.get('csrfToken'),




  // --------------------------------------------------
  //   通知
  // --------------------------------------------------

  notificationActiveType: state.getIn(['notificationObj', 'activeType']),
  notificationUnreadCount: state.getIn(['notificationObj', 'unreadCount']),
  notificationUnreadTotal: state.getIn(['notificationObj', 'unreadTotal']),
  notificationUnreadList: state.getIn(['notificationObj', 'unreadList']),
  notificationUnreadActivePage: state.getIn(['notificationObj', 'unreadActivePage']),
  notificationAlreadyReadTotal: state.getIn(['notificationObj', 'alreadyReadTotal']),
  notificationAlreadyReadList: state.getIn(['notificationObj', 'alreadyReadList']),
  notificationAlreadyReadActivePage: state.getIn(['notificationObj', 'alreadyReadActivePage']),
  notificationLimitNotification: state.getIn(['notificationObj', 'limitNotification']),


  // --------------------------------------------------
  //   ヘッダー
  // --------------------------------------------------

  headerHeroImageId: state.getIn(['headerObj', 'heroImageId']),
  headerHeroImageRenewalDate: state.getIn(['headerObj', 'heroImageRenewalDate']),

  headerCommunityNo: state.getIn(['headerObj', 'communityNo']),
  headerCommunityRenewalDate: state.getIn(['headerObj', 'communityRenewalDate']),
  headerCommunityId: state.getIn(['headerObj', 'communityId']),
  headerCommunityName: state.getIn(['headerObj', 'communityName']),

  headerGameNo: state.getIn(['headerObj', 'gameNo']),
  headerGameRenewalDate: state.getIn(['headerObj', 'gameRenewalDate']),
  headerGameId: state.getIn(['headerObj', 'gameId']),
  headerGameName: state.getIn(['headerObj', 'gameName']),
  headerGameSubtitle: state.getIn(['headerObj', 'gameSubtitle']),
  headerGameThumbnail: state.getIn(['headerObj', 'gameThumbnail']),
  headerGameReleaseDate1: state.getIn(['headerObj', 'gameReleaseDate1']),
  headerGameReleaseDate2: state.getIn(['headerObj', 'gameReleaseDate2']),
  headerGameReleaseDate3: state.getIn(['headerObj', 'gameReleaseDate3']),
  headerGameReleaseDate4: state.getIn(['headerObj', 'gameReleaseDate4']),
  headerGameReleaseDate5: state.getIn(['headerObj', 'gameReleaseDate5']),
  headerGamePlayersMax: state.getIn(['headerObj', 'gamePlayersMax']),
  headerGameHardwareList: state.getIn(['headerObj', 'gameHardwareArr']),
  headerGameGenreList: state.getIn(['headerObj', 'gameGenreArr']),
  headerGameDeveloperList: state.getIn(['headerObj', 'gameDeveloperArr']),
  headerGameLinkList: state.getIn(['headerObj', 'gameLinkArr']),


  // --------------------------------------------------
  //   フッター
  // --------------------------------------------------

  footerCardType: state.getIn(['footerObj', 'cardType']),
  footerCardGameCommunityRenewalList: state.getIn(['footerObj', 'gameCommunityRenewalArr']),
  footerCardGameCommunityAccessList: state.getIn(['footerObj', 'gameCommunityAccessArr']),
  footerCardUserCommunityAccessList: state.getIn(['footerObj', 'userCommunityAccessArr']),


  // --------------------------------------------------
  //   フッター / モーダル
  // --------------------------------------------------

  modalNotificationShow: state.getIn(['modalObj', 'notification', 'show']),


});



// --------------------------------------------------
//   mapDispatchToProps
// --------------------------------------------------

const mapDispatchToProps = (dispatch) => {

  const bindActionObj = bindActionCreators(actions, dispatch);


  // $(document).on('click', '#jslink', function(e) {
  //
  //   e.preventDefault();
  //
  //   var target = '_self';
  //   var address = $(this).data('jslink');
  //
  //   window.open(address, target);
  //
  // });


  // bindActionObj.funcJavascriptLink = () => {
  //
  //   // e.preventDefault();
  //   console.log('funcJavascriptLink');
  //   // console.log('e = ', e);
  //
  //   // window.open(url, '_self');
  //
  // };


  // bindActionObj.funcLightbox = (url) => {
  //
  //   console.log('funcLightbox');
  //   // console.log('stateModel = ', stateModel);
  //
  //   $.magnificPopup.open({
  //     items: {
  //       src: url
  //     },
  //     type: 'image'
  //   });
  //
  //   // $.magnificPopup.open({
  //   //   items: {
  //   //     src: url
  //   //   },
  //   //   type: 'iframe'
  //   // });
  //
  // };



  /**
   * Promise / APIにアクセスしてJSONで取得したオブジェクトを返す
   * @param  {string} urlBase    基本のURL
   * @param  {FormData} formData new FormData()で作成したインスタンス
   * @return {Object}            オブジェクト
   */
  const funcPromise = (urlBase, formData) => new Promise((resolve) => {

    fetch(`${urlBase}api/react.json`, {
      method: 'POST',
      credentials: 'include',
      mode: 'same-origin',
      body: formData
    })
      .then((response) => {
        if (response.ok) {
          return response.json();
        }
      })
      .then((jsonObj) => {
        resolve(jsonObj);
      });

  });







  /**
   * モーダルを開く / 同時に通知データを読み込む
   * @param  {Model}  stateModel State
   * @param  {boolean}  show     モーダルを開く場合は true、閉じる場合は false
   */
  bindActionObj.funcSelectModalNotification = async (stateModel, show) => {


    // --------------------------------------------------
    //   Get Data
    // --------------------------------------------------

    const urlBase = stateModel.get('urlBase');
    const activeType = stateModel.getIn(['notificationObj', 'activeType']);

    let activePage = 1;

    if (activeType === 'unread') {
      activePage = stateModel.getIn(['notificationObj', 'unreadActivePage']);
    } else {
      activePage = stateModel.getIn(['notificationObj', 'alreadyReadActivePage']);
    }


    // --------------------------------------------------
    //   FormData
    // --------------------------------------------------

    const formData = new FormData();

    formData.append('apiType', 'selectNotification');
    formData.append('readType', activeType);
    formData.append('page', activePage);


    // --------------------------------------------------
    //   Await & Dispatch
    // --------------------------------------------------

    try {

      const returnObj = await funcPromise(urlBase, formData);

      console.log('returnObj = ', returnObj);


      if (returnObj.error) {
        throw new Error();
      }


      let unreadTotal = null;
      let unreadArr = null;
      let alreadyReadTotal = null;
      let alreadyReadArr = null;

      if (activeType === 'unread') {
        unreadTotal = returnObj.total;
        unreadArr = returnObj.dataArr;
      } else {
        alreadyReadTotal = returnObj.total;
        alreadyReadArr = returnObj.dataArr;
      }

      // console.log('show = ', show);


      dispatch(actions.funcSelectModalNotification(show, unreadTotal, unreadArr, alreadyReadTotal, alreadyReadArr, activePage));

    } catch (e) {
      // continue regardless of error
    }

  };



  /**
   * 通知の未読数を取得
   * @param  {Model}  stateModel State
   */
  bindActionObj.funcSelectNotificationUnreadCount = async (stateModel) => {

    // console.log('funcSelectNotificationUnreadCount');
    // console.log('stateModel = ', stateModel);


    // --------------------------------------------------
    //   Get Data
    // --------------------------------------------------

    const urlBase = stateModel.get('urlBase');


    // --------------------------------------------------
    //   FormData
    // --------------------------------------------------

    const formData = new FormData();

    formData.append('apiType', 'selectNotificationUnreadCount');


    // --------------------------------------------------
    //   Await & Dispatch
    // --------------------------------------------------

    try {

      const returnObj = await funcPromise(urlBase, formData);

      // console.log('returnObj = ', returnObj);

      if (returnObj.error) {
        throw new Error();
      }

      dispatch(actions.funcSelectNotificationUnreadCount(returnObj.unreadCount));

    } catch (e) {
      // continue regardless of error
    }

  };



  /**
   * 通知のページを切り替える
   * @param  {Model}  stateModel  State
   * @param  {number}  activePage 表示するページ
   */
  bindActionObj.funcSelectNotification = async (stateModel, currentTarget, readType, activePage) => {

    // console.log('funcSelectNotification');
    // console.log('readType = ', readType);


    // --------------------------------------------------
    //   Loading Start
    // --------------------------------------------------

    // console.log('currentTarget = ', currentTarget);

    let instanceLadda = null;

    if (currentTarget) {
      instanceLadda = Ladda.create(currentTarget);
      instanceLadda.start();
    }


    // --------------------------------------------------
    //   Get Data
    // --------------------------------------------------

    const urlBase = stateModel.get('urlBase');
    // const activeType = stateModel.getIn(['notificationObj', 'activeType']);
    // console.log('activeType = ', activeType);


    // --------------------------------------------------
    //   FormData
    // --------------------------------------------------

    const formData = new FormData();

    formData.append('apiType', 'selectNotification');
    formData.append('readType', readType);
    formData.append('page', activePage);


    // --------------------------------------------------
    //   Await & Dispatch
    // --------------------------------------------------

    try {

      const returnObj = await funcPromise(urlBase, formData);

      // console.log('returnObj = ', returnObj);

      if (returnObj.error) {
        throw new Error();
      }


      let unreadTotal = null;
      let unreadArr = null;
      let alreadyReadTotal = null;
      let alreadyReadArr = null;

      if (readType === 'unread') {
        unreadTotal = returnObj.total;
        unreadArr = returnObj.dataArr;
      } else {
        alreadyReadTotal = returnObj.total;
        alreadyReadArr = returnObj.dataArr;
      }

      dispatch(actions.funcSelectModalNotification(true, unreadTotal, unreadArr, alreadyReadTotal, alreadyReadArr, activePage));

    } catch (e) {
      // continue regardless of error
    }


    // --------------------------------------------------
    //   Loading Stop
    // --------------------------------------------------

    if (currentTarget) {
      instanceLadda.stop();
    }


  };



  /**
   * 通知 / すべて既読にする
   * @param  {Model}  stateModel  State
   * @param  {number}  activePage 表示するページ
   */
  bindActionObj.funcUpdateAllUnreadToAlreadyRead = async (stateModel, currentTarget) => {

    console.log('funcUpdateAllUnreadToAlreadyRead');


    // --------------------------------------------------
    //   Loading Start
    // --------------------------------------------------

    let instanceLadda = null;

    if (currentTarget) {
      instanceLadda = Ladda.create(currentTarget);
      instanceLadda.start();
    }


    // --------------------------------------------------
    //   Get Data
    // --------------------------------------------------

    const urlBase = stateModel.get('urlBase');


    // --------------------------------------------------
    //   FormData
    // --------------------------------------------------

    const formData = new FormData();

    formData.append('apiType', 'updateAllUnreadToAlreadyRead');


    // --------------------------------------------------
    //   Await & Dispatch
    // --------------------------------------------------

    try {

      const returnObj = await funcPromise(urlBase, formData);

      console.log('returnObj = ', returnObj);

      if (returnObj.error) {
        throw new Error();
      }


      // let unreadTotal = null;
      // let unreadArr = null;
      // let alreadyReadTotal = null;
      // let alreadyReadArr = null;
      //
      // if (readType === 'unread') {
      //   unreadTotal = returnObj.total;
      //   unreadArr = returnObj.dataArr;
      // } else {
      //   alreadyReadTotal = returnObj.total;
      //   alreadyReadArr = returnObj.dataArr;
      // }
      //
      dispatch(actions.funcSelectModalNotification(true, 0, [], null, null, 1));

    } catch (e) {
      // continue regardless of error
    }


    // --------------------------------------------------
    //   Loading Stop
    // --------------------------------------------------

    if (currentTarget) {
      instanceLadda.stop();
    }


  };



  /**
   * フッターのカードを切り替える
   * 最近更新されたゲームコミュニティ / 最近アクセスしたゲームコミュニティ / 最近アクセスしたユーザーコミュニティ
   * @param  {Model}  stateModel State
   * @param  {string}  cardType gameCommunityRenewal / gameCommunityAccess / userCommunityAccess
   */
  bindActionObj.funcSelectFooterCardType = async (stateModel, cardType) => {


    // --------------------------------------------------
    //   Get Data
    // --------------------------------------------------

    const urlBase = stateModel.get('urlBase');


    // --------------------------------------------------
    //   FormData
    // --------------------------------------------------

    const formData = new FormData();

    formData.append('apiType', 'selectFooterCard');
    formData.append('cardType', cardType);


    // --------------------------------------------------
    //   クッキー
    // --------------------------------------------------

    Cookies.set('footerCardType', cardType, { expires: 1, path: '', domain: location.hostname, secure: true });


    // --------------------------------------------------
    //   Await & Dispatch
    // --------------------------------------------------

    try {

      const returnObj = await funcPromise(urlBase, formData);

      // console.log('returnObj = ', returnObj);

      if (returnObj.error) {
        throw new Error();
      }


      let gameCommunityRenewalArr = null;

      if (returnObj.gameCommunityRenewalArr) {
        gameCommunityRenewalArr = returnObj.gameCommunityRenewalArr;
      }

      let gameCommunityAccessArr = null;

      if (returnObj.gameCommunityAccessArr) {
        gameCommunityAccessArr = returnObj.gameCommunityAccessArr;
      }

      let userCommunityAccessArr = null;

      if (returnObj.userCommunityAccessArr) {
        userCommunityAccessArr = returnObj.userCommunityAccessArr;
      }

      // console.log('gameCommunityRenewalArr = ', gameCommunityRenewalArr);
      // console.log('gameCommunityAccessArr = ', gameCommunityAccessArr);
      // console.log('userCommunityAccessArr = ', userCommunityAccessArr);


      dispatch(actions.funcSelectFooterCardType(cardType, gameCommunityRenewalArr, gameCommunityAccessArr, userCommunityAccessArr));

    } catch (e) {
      // continue regardless of error
    }

  };



  bindActionObj.funcInitialAsynchronous = async (stateModel) => {

    // bindActionObj.funcSelectNotificationUnreadCount(stateModel);

  };



  return bindActionObj;

};



const ContainerRoot = connect(
  mapStateToProps,
  mapDispatchToProps
)(Root);



export default ContainerRoot;
