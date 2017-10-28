// --------------------------------------------------
//   Import
// --------------------------------------------------

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import 'whatwg-fetch';
import Cookies from 'js-cookie';

import fetchApi from '../modules/api';
import Root from '../components/root';

import * as actions from '../actions/action';



// --------------------------------------------------
//   mapStateToProps
// --------------------------------------------------

const mapStateToProps = (state) => {

  const reducerRootMap = state.reducerRoot;
  // const reducerAppMap = state.reducerApp;

  // console.log('state = ', state);
  // console.log('reducerRootMap = ', reducerRootMap.toJS());
  // console.log('reducerAppMap = ', reducerAppMap.toJS());


  // const reducerCurrentMap = state.reducerApp;


  return ({


    // --------------------------------------------------
    //   共通
    // --------------------------------------------------

    stateModel: reducerRootMap,

    baseName: reducerRootMap.get('baseName'),
    urlDirectory1: reducerRootMap.get('urlDirectory1'),
    urlDirectory2: reducerRootMap.get('urlDirectory2'),
    urlDirectory3: reducerRootMap.get('urlDirectory3'),

    deviceType: reducerRootMap.get('deviceType'),
    deviceOs: reducerRootMap.get('deviceOs'),
    host: reducerRootMap.get('host'),
    userAgent: reducerRootMap.get('userAgent'),
    userNo: reducerRootMap.get('userNo'),
    playerId: reducerRootMap.get('playerId'),
    language: reducerRootMap.get('language'),
    urlBase: reducerRootMap.get('urlBase'),
    adBlock: reducerRootMap.get('adBlock'),
    paginationColumn: reducerRootMap.get('paginationColumn'),
    csrfToken: reducerRootMap.get('csrfToken'),



    // --------------------------------------------------
    //   Stripe
    // --------------------------------------------------

    stripePublishableKey: reducerRootMap.get('stripePublishableKey'),



    // --------------------------------------------------
    //   通知
    // --------------------------------------------------

    notificationActiveType: reducerRootMap.getIn(['notificationMap', 'activeType']),
    notificationUnreadCount: reducerRootMap.getIn(['notificationMap', 'unreadCount']),
    notificationUnreadTotal: reducerRootMap.getIn(['notificationMap', 'unreadTotal']),
    notificationUnreadList: reducerRootMap.getIn(['notificationMap', 'unreadArr']),
    notificationUnreadActivePage: reducerRootMap.getIn(['notificationMap', 'unreadActivePage']),
    notificationAlreadyReadTotal: reducerRootMap.getIn(['notificationMap', 'alreadyReadTotal']),
    notificationAlreadyReadList: reducerRootMap.getIn(['notificationMap', 'alreadyReadArr']),
    notificationAlreadyReadActivePage: reducerRootMap.getIn(['notificationMap', 'alreadyReadActivePage']),
    notificationLimitNotification: reducerRootMap.getIn(['notificationMap', 'limitNotification']),



    // --------------------------------------------------
    //   ヘッダー
    // --------------------------------------------------

    headerHeroImageId: reducerRootMap.getIn(['headerMap', 'heroImageId']),
    headerHeroImageRenewalDate: reducerRootMap.getIn(['headerMap', 'heroImageRenewalDate']),

    headerCommunityNo: reducerRootMap.getIn(['headerMap', 'communityNo']),
    headerCommunityRenewalDate: reducerRootMap.getIn(['headerMap', 'communityRenewalDate']),
    headerCommunityId: reducerRootMap.getIn(['headerMap', 'communityId']),
    headerCommunityName: reducerRootMap.getIn(['headerMap', 'communityName']),

    headerGameNo: reducerRootMap.getIn(['headerMap', 'gameNo']),
    headerGameRenewalDate: reducerRootMap.getIn(['headerMap', 'gameRenewalDate']),
    headerGameId: reducerRootMap.getIn(['headerMap', 'gameId']),
    headerGameName: reducerRootMap.getIn(['headerMap', 'gameName']),
    headerGameSubtitle: reducerRootMap.getIn(['headerMap', 'gameSubtitle']),
    headerGameThumbnail: reducerRootMap.getIn(['headerMap', 'gameThumbnail']),
    headerGameReleaseDate1: reducerRootMap.getIn(['headerMap', 'gameReleaseDate1']),
    headerGameReleaseDate2: reducerRootMap.getIn(['headerMap', 'gameReleaseDate2']),
    headerGameReleaseDate3: reducerRootMap.getIn(['headerMap', 'gameReleaseDate3']),
    headerGameReleaseDate4: reducerRootMap.getIn(['headerMap', 'gameReleaseDate4']),
    headerGameReleaseDate5: reducerRootMap.getIn(['headerMap', 'gameReleaseDate5']),
    headerGamePlayersMax: reducerRootMap.getIn(['headerMap', 'gamePlayersMax']),
    headerGameHardwareList: reducerRootMap.getIn(['headerMap', 'gameHardwareList']),
    headerGameGenreList: reducerRootMap.getIn(['headerMap', 'gameGenreList']),
    headerGameDeveloperList: reducerRootMap.getIn(['headerMap', 'gameDeveloperList']),
    headerGameLinkList: reducerRootMap.getIn(['headerMap', 'gameLinkList']),

    headerMenuMap: reducerRootMap.getIn(['menuMap', 'headerMap']),



    // --------------------------------------------------
    //   メニュー
    // --------------------------------------------------

    menuMap: reducerRootMap.getIn(['menuMap', 'mainMap']),
    menuDrawerActive: reducerRootMap.getIn(['menuMap', 'drawerActive']),



    // --------------------------------------------------
    //   モーダル
    // --------------------------------------------------

    modalNotificationShow: reducerRootMap.getIn(['modalMap', 'notification', 'show']),



    // --------------------------------------------------
    //   フッター
    // --------------------------------------------------

    footerCardType: reducerRootMap.getIn(['footerMap', 'cardType']),
    footerCardGameCommunityRenewalList: reducerRootMap.getIn(['footerMap', 'gameCommunityRenewalList']),
    footerCardGameCommunityAccessList: reducerRootMap.getIn(['footerMap', 'gameCommunityAccessList']),
    footerCardUserCommunityAccessList: reducerRootMap.getIn(['footerMap', 'userCommunityAccessList']),


  });

};



// --------------------------------------------------
//   mapDispatchToProps
// --------------------------------------------------

const mapDispatchToProps = (dispatch) => {

  const bindActionObj = bindActionCreators(actions, dispatch);



  // --------------------------------------------------
  //   URL 変更
  // --------------------------------------------------

  /**
   * URL を変更する
   * https://gameusers.org/urlDirectory1/urlDirectory2/urlDirectory3
   * @param  {string}  urlDirectory1 URL1
   * @param  {string}  urlDirectory2 URL2
   * @param  {string}  urlDirectory3 URL3
   */
  bindActionObj.funcUrlDirectory = async (urlDirectory1, urlDirectory2, urlDirectory3) => {


    // --------------------------------------------------
    //   Await & Dispatch
    // --------------------------------------------------

    try {


      dispatch(actions.funcUrlDirectory(urlDirectory1, urlDirectory2, urlDirectory3));



      // --------------------------------------------------
      //   ページ上部に移動
      // --------------------------------------------------

      const selector = document.querySelector('main');
      const clientRect = selector.getBoundingClientRect();
      const { top } = clientRect;
      const pageY = window.pageYOffset + top;
      // const margin = this.props.deviceType === 'other' ? 70 : 50;
      // console.log('margin = ', margin);
      window.scrollTo(0, pageY - 70);


    } catch (e) {
      // continue regardless of error
    }

  };




  // --------------------------------------------------
  //   通知
  // --------------------------------------------------

  /**
   * 通知のモーダルを開く / 通知データを読み込む
   * @param  {Model}  stateModel Modelクラスのインスタンス
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

      // console.log('urlBase = ', `${urlBase}api/react.json`);
      // const returnObj = await promiseReactJsonPost(urlBase, formData);

      const returnObj = await fetchApi(`${urlBase}api/react.json`, 'POST', 'same-origin', 'same-origin', formData);

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
   * 通知のモーダルを閉じる / 予約IDを既読IDにする / 未読の総数を取得する
   * @param  {Model}  stateModel Modelクラスのインスタンス
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

      // const returnObj1 = await promiseReactJsonPost(urlBase, formData1);
      const returnObj1 = await fetchApi(`${urlBase}api/react.json`, 'POST', 'same-origin', 'same-origin', formData1);

      // console.log('returnObj1 = ', returnObj1);

      if (returnObj1.error) {
        throw new Error();
      }


      // const returnObj2 = await promiseReactJsonPost(urlBase, formData2);
      const returnObj2 = await fetchApi(`${urlBase}api/react.json`, 'POST', 'same-origin', 'same-origin', formData2);

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
   * @param  {Model}  stateModel Modelクラスのインスタンス
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

      // const returnObj = await promiseReactJsonPost(urlBase, formData);
      const returnObj = await fetchApi(`${urlBase}api/react.json`, 'POST', 'same-origin', 'same-origin', formData);

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
   * @param  {Model}    stateModel    Modelクラスのインスタンス
   * @param  {element}  currentTarget エレメント
   * @param  {string}   readType      unread / alreadyRead
   * @param  {number}   activePage    表示するページ
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


      // const returnObj = await promiseReactJsonPost(urlBase, formData);
      const returnObj = await fetchApi(`${urlBase}api/react.json`, 'POST', 'same-origin', 'same-origin', formData);

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
   * @param  {Model}  stateModel Modelクラスのインスタンス
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


      // const returnObj = await promiseReactJsonPost(urlBase, formData);
      const returnObj = await fetchApi(`${urlBase}api/react.json`, 'POST', 'same-origin', 'same-origin', formData);

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
   * @param  {Model}    stateModel    Modelクラスのインスタンス
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

      // const returnObj = await promiseReactJsonPost(urlBase, formData);
      const returnObj = await fetchApi(`${urlBase}api/react.json`, 'POST', 'same-origin', 'same-origin', formData);

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




  // --------------------------------------------------
  //   フッター
  // --------------------------------------------------

  /**
   * フッターのカードを切り替える
   * 最近更新されたゲームコミュニティ / 最近アクセスしたゲームコミュニティ / 最近アクセスしたユーザーコミュニティ
   * @param  {Model}   stateModel Modelクラスのインスタンス
   * @param  {string}  cardType   gameCommunityRenewal / gameCommunityAccess / userCommunityAccess
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

    Cookies.set('footerCardType', cardType,
      {
        expires: 1,
        path: '',
        domain: window.location.hostname,
        secure: true
      }
    );


    // --------------------------------------------------
    //   Await & Dispatch
    // --------------------------------------------------

    try {


      // const returnObj = await promiseReactJsonPost(urlBase, formData);
      const returnObj = await fetchApi(`${urlBase}api/react.json`, 'POST', 'same-origin', 'same-origin', formData);

      // console.log('returnObj = ', returnObj);

      if (returnObj.error) {
        throw new Error();
      }


      let gameCommunityRenewalList = null;

      if (returnObj.gameCommunityRenewalList) {
        ({ gameCommunityRenewalList } = returnObj);
      }

      let gameCommunityAccessList = null;

      if (returnObj.gameCommunityAccessList) {
        ({ gameCommunityAccessList } = returnObj);
      }

      let userCommunityAccessList = null;

      if (returnObj.userCommunityAccessList) {
        ({ userCommunityAccessList } = returnObj);
      }

      // console.log('gameCommunityRenewalList = ', gameCommunityRenewalList);
      // console.log('gameCommunityAccessList = ', gameCommunityAccessList);
      // console.log('userCommunityAccessList = ', userCommunityAccessList);


      dispatch(actions.funcSelectFooterCardType(cardType, gameCommunityRenewalList, gameCommunityAccessList, userCommunityAccessList));


    } catch (e) {
      // continue regardless of error
    }

  };



  bindActionObj.funcInitialAsynchronous = async () => {

  };



  return bindActionObj;

};



const ContainerRoot = connect(
  mapStateToProps,
  mapDispatchToProps
)(Root);



export default ContainerRoot;
