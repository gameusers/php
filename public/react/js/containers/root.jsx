// --------------------------------------------------
//   Import
// --------------------------------------------------

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import 'whatwg-fetch';
import Cookies from 'js-cookie';

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

  notificationActiveType: state.getIn(['notificationMap', 'activeType']),
  notificationUnreadCount: state.getIn(['notificationMap', 'unreadCount']),
  notificationUnreadTotal: state.getIn(['notificationMap', 'unreadTotal']),
  notificationUnreadList: state.getIn(['notificationMap', 'unreadArr']),
  notificationUnreadActivePage: state.getIn(['notificationMap', 'unreadActivePage']),
  notificationAlreadyReadTotal: state.getIn(['notificationMap', 'alreadyReadTotal']),
  notificationAlreadyReadList: state.getIn(['notificationMap', 'alreadyReadArr']),
  notificationAlreadyReadActivePage: state.getIn(['notificationMap', 'alreadyReadActivePage']),
  notificationLimitNotification: state.getIn(['notificationMap', 'limitNotification']),



  // --------------------------------------------------
  //   ヘッダー
  // --------------------------------------------------

  headerHeroImageId: state.getIn(['headerMap', 'heroImageId']),
  headerHeroImageRenewalDate: state.getIn(['headerMap', 'heroImageRenewalDate']),

  headerCommunityNo: state.getIn(['headerMap', 'communityNo']),
  headerCommunityRenewalDate: state.getIn(['headerMap', 'communityRenewalDate']),
  headerCommunityId: state.getIn(['headerMap', 'communityId']),
  headerCommunityName: state.getIn(['headerMap', 'communityName']),

  headerGameNo: state.getIn(['headerMap', 'gameNo']),
  headerGameRenewalDate: state.getIn(['headerMap', 'gameRenewalDate']),
  headerGameId: state.getIn(['headerMap', 'gameId']),
  headerGameName: state.getIn(['headerMap', 'gameName']),
  headerGameSubtitle: state.getIn(['headerMap', 'gameSubtitle']),
  headerGameThumbnail: state.getIn(['headerMap', 'gameThumbnail']),
  headerGameReleaseDate1: state.getIn(['headerMap', 'gameReleaseDate1']),
  headerGameReleaseDate2: state.getIn(['headerMap', 'gameReleaseDate2']),
  headerGameReleaseDate3: state.getIn(['headerMap', 'gameReleaseDate3']),
  headerGameReleaseDate4: state.getIn(['headerMap', 'gameReleaseDate4']),
  headerGameReleaseDate5: state.getIn(['headerMap', 'gameReleaseDate5']),
  headerGamePlayersMax: state.getIn(['headerMap', 'gamePlayersMax']),
  headerGameHardwareList: state.getIn(['headerMap', 'gameHardwareList']),
  headerGameGenreList: state.getIn(['headerMap', 'gameGenreList']),
  headerGameDeveloperList: state.getIn(['headerMap', 'gameDeveloperList']),
  headerGameLinkList: state.getIn(['headerMap', 'gameLinkList']),

  headerMenuMap: state.getIn(['headerMap', 'menuMap']),



  // --------------------------------------------------
  //   メニュー
  // --------------------------------------------------

  menuMap: state.get('menuMap'),
  menuDrawerActive: state.getIn(['menuMap', 'drawerActive']),



  // --------------------------------------------------
  //   モーダル
  // --------------------------------------------------

  modalNotificationShow: state.getIn(['modalMap', 'notification', 'show']),



  // --------------------------------------------------
  //   フッター
  // --------------------------------------------------

  footerCardType: state.getIn(['footerMap', 'cardType']),
  footerCardGameCommunityRenewalList: state.getIn(['footerMap', 'gameCommunityRenewalList']),
  footerCardGameCommunityAccessList: state.getIn(['footerMap', 'gameCommunityAccessList']),
  footerCardUserCommunityAccessList: state.getIn(['footerMap', 'userCommunityAccessList']),



});



// --------------------------------------------------
//   mapDispatchToProps
// --------------------------------------------------

const mapDispatchToProps = (dispatch) => {

  const bindActionObj = bindActionCreators(actions, dispatch);



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
   * モーダルを開く / 通知データを読み込む
   * @param  {Model}  stateModel State
   */
  bindActionObj.funcShowModalNotification = async (stateModel) => {


    // --------------------------------------------------
    //   Get Data
    // --------------------------------------------------

    const urlBase = stateModel.get('urlBase');
    const activeType = stateModel.getIn(['notificationMap', 'activeType']);

    let activePage = 1;

    if (activeType === 'unread') {
      activePage = stateModel.getIn(['notificationMap', 'unreadActivePage']);
    } else {
      activePage = stateModel.getIn(['notificationMap', 'alreadyReadActivePage']);
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


      // --------------------------------------------------
      //   通知データ更新
      // --------------------------------------------------

      const returnObj = await funcPromise(urlBase, formData);

      // console.log('returnObj = ', returnObj);

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

      dispatch(actions.funcNotificationMap(unreadTotal, unreadArr, alreadyReadTotal, alreadyReadArr, activePage));


      // --------------------------------------------------
      //   モーダルを開く
      // --------------------------------------------------

      dispatch(actions.funcModalMapNotificationShow(true));


    } catch (e) {
      // continue regardless of error
    }

  };



  /**
   * モーダルを閉じる / 予約IDを既読IDにする / 未読の総数を取得する
   * @param  {Model}  stateModel State
   */
  bindActionObj.funcHideModalNotification = async (stateModel) => {

    // console.log('funcHideModalNotification');


    // --------------------------------------------------
    //   Get Data
    // --------------------------------------------------

    const urlBase = stateModel.get('urlBase');


    // --------------------------------------------------
    //   FormData1
    // --------------------------------------------------

    const formData1 = new FormData();

    formData1.append('apiType', 'updateReservationIdToAlreadyReadId');


    // --------------------------------------------------
    //   FormData2
    // --------------------------------------------------

    const formData2 = new FormData();

    formData2.append('apiType', 'selectNotificationUnreadCount');


    // --------------------------------------------------
    //   Await & Dispatch
    // --------------------------------------------------

    try {


      // --------------------------------------------------
      //   モーダルを閉じる
      // --------------------------------------------------

      dispatch(actions.funcModalMapNotificationShow(false));


      // --------------------------------------------------
      //   unreadActivePage & alreadyReadActivePage を 1 にする
      // --------------------------------------------------

      dispatch(actions.funcNotificationMapResetActivePage());


      // --------------------------------------------------
      //   未読の総数変更
      // --------------------------------------------------

      const returnObj1 = await funcPromise(urlBase, formData1);

      // console.log('returnObj1 = ', returnObj1);

      if (returnObj1.error) {
        throw new Error();
      }


      const returnObj2 = await funcPromise(urlBase, formData2);

      // console.log('returnObj2 = ', returnObj2);

      if (returnObj2.error) {
        throw new Error();
      }

      dispatch(actions.funcNotificationMapUnreadCount(returnObj2.unreadCount));


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


      dispatch(actions.funcNotificationMapUnreadCount(returnObj.unreadCount));


    } catch (e) {
      // continue regardless of error
    }

  };



  /**
   * 通知を取得してページを切り替える
   * @param  {Model}  stateModel State
   * @param  {element}  currentTarget エレメント
   * @param  {string}  readType unread / alreadyRead
   * @param  {number}  activePage 表示するページ
   */
  bindActionObj.funcSelectNotification = async (stateModel, currentTarget, readType, activePage) => {

    // console.log('funcSelectNotification');
    // console.log('readType = ', readType);
    // console.log('currentTarget = ', currentTarget);


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
    // const activeType = stateModel.getIn(['notificationMap', 'activeType']);
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

      dispatch(actions.funcNotificationMap(unreadTotal, unreadArr, alreadyReadTotal, alreadyReadArr, activePage));


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
   * 通知 / 予約通知を既読通知にする / 未読通知の総数も取得
   * @param  {Model}  stateModel State
   */
  bindActionObj.funcUpdateReservationIdToAlreadyReadId = async (stateModel) => {

    // console.log('funcUpdateReservationIdToAlreadyReadId');


    // --------------------------------------------------
    //   Get Data
    // --------------------------------------------------

    const urlBase = stateModel.get('urlBase');


    // --------------------------------------------------
    //   FormData
    // --------------------------------------------------

    const formData = new FormData();

    formData.append('apiType', 'updateReservationIdToAlreadyReadId');


    // --------------------------------------------------
    //   Await & Dispatch
    // --------------------------------------------------

    try {


      const returnObj = await funcPromise(urlBase, formData);

      // console.log('returnObj = ', returnObj);

      if (returnObj.error) {
        throw new Error();
      }


      dispatch(actions.funcNotificationMap(0, [], null, null, 1));


    } catch (e) {
      // continue regardless of error
    }

  };



  /**
   * 通知 / 未読をすべて既読にする
   * @param  {Model}  stateModel State
   * @param  {element}  currentTarget エレメント
   */
  bindActionObj.funcUpdateAllUnreadToAlreadyRead = async (stateModel, currentTarget) => {

    // console.log('funcUpdateAllUnreadToAlreadyRead');


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

      // console.log('returnObj = ', returnObj);

      if (returnObj.error) {
        throw new Error();
      }


      dispatch(actions.funcNotificationMap(0, [], null, null, 1));


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


      let gameCommunityRenewalList = null;

      if (returnObj.gameCommunityRenewalList) {
        gameCommunityRenewalList = returnObj.gameCommunityRenewalList;
      }

      let gameCommunityAccessList = null;

      if (returnObj.gameCommunityAccessList) {
        gameCommunityAccessList = returnObj.gameCommunityAccessList;
      }

      let userCommunityAccessList = null;

      if (returnObj.userCommunityAccessList) {
        userCommunityAccessList = returnObj.userCommunityAccessList;
      }

      // console.log('gameCommunityRenewalList = ', gameCommunityRenewalList);
      // console.log('gameCommunityAccessList = ', gameCommunityAccessList);
      // console.log('userCommunityAccessList = ', userCommunityAccessList);


      dispatch(actions.funcSelectFooterCardType(cardType, gameCommunityRenewalList, gameCommunityAccessList, userCommunityAccessList));


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
