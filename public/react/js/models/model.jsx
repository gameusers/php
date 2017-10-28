// --------------------------------------------------
//   Import
// --------------------------------------------------

import { Record } from 'immutable';

import { fromJSOrdered } from '../modules/package';
import menuObj from '../../contents/menu';



// --------------------------------------------------
//   initial State 取得（サーバーから受け取る初期データ）
// --------------------------------------------------

const initialStateObj = gameUsersInitialStateObj();


// --------------------------------------------------
//   BrowserRouter の basename（react-router-dom の記述で利用する）
//   開発環境の場合、正しくアクセスできるリンクにするために基本のURLを設定する必要がある
// --------------------------------------------------

initialStateObj.baseName = '/gameusers/public';

if (window.location.hostname === 'gameusers.org') {
  initialStateObj.baseName = '';
}


// --------------------------------------------------
//   URL Directory パスを分解したもの
//   現在どの場所にいて、どのコンテンツを表示するかの判定に利用する
//   https://gameusers.org/urlDirectory1/urlDirectory2/urlDirectory3
// --------------------------------------------------

let tempArr = window.location.href.split(initialStateObj.urlBase);
tempArr = tempArr[1].split('/');

initialStateObj.urlDirectory1 = tempArr[0] || null;
initialStateObj.urlDirectory2 = tempArr[1] || null;
initialStateObj.urlDirectory3 = tempArr[2] || null;


// --------------------------------------------------
//   通知
// --------------------------------------------------

initialStateObj.notificationMap.activeType = 'unread';
initialStateObj.notificationMap.unreadTotal = 10;
initialStateObj.notificationMap.unreadArr = [];
initialStateObj.notificationMap.unreadActivePage = 1;
initialStateObj.notificationMap.alreadyReadTotal = 10;
initialStateObj.notificationMap.alreadyReadArr = [];
initialStateObj.notificationMap.alreadyReadActivePage = 1;


// --------------------------------------------------
//   モーダル
// --------------------------------------------------

initialStateObj.modalMap = {
  notification: {
    show: false
  }
};


// --------------------------------------------------
//   フッターに表示するカードの種類を指定
//   gameCommunityRenewal / 最近更新されたゲームコミュニティ
//   gameCommunityAccess / 最近アクセスしたゲームコミュニティ
//   userCommunityAccess / 最近アクセスしたユーザーコミュニティ
// --------------------------------------------------

if (initialStateObj.footerMap.gameCommunityAccessList) {
  initialStateObj.footerMap.cardType = 'gameCommunityAccess';
} else if (initialStateObj.footerMap.userCommunityAccessList) {
  initialStateObj.footerMap.cardType = 'userCommunityAccess';
} else {
  initialStateObj.footerMap.cardType = 'gameCommunityRenewal';
}


// --------------------------------------------------
//   メニュー
//   contents/menu.jsx から menuObj を読み込んで代入
// --------------------------------------------------

initialStateObj.menuMap = menuObj;
initialStateObj.menuMap.drawerActive = false;


// ---------------------------------------------
//   - urlDirectory3 のあるページに最初にアクセスした場合
//   （例：https://localhost/gameusers/public/app/pay/vendor）
//   ヘッダーの activeUrlDirectory3 を代入する。現在開いてるページを保存しておくため
//   ヘッダーのリンクを切り替えても保存されたページがまた開くように
// ---------------------------------------------

if (initialStateObj.urlDirectory3) {
  initialStateObj.menuMap.headerMap[initialStateObj.urlDirectory1][initialStateObj.urlDirectory2].activeUrlDirectory3 = initialStateObj.urlDirectory3;
}



// console.log('initialStateObj = ', initialStateObj);
// console.log('Cookies.get() = ', Cookies.get());




// --------------------------------------------------
//   Class Model
//   Immutable.js の Reacord クラスを継承して Model クラスを作成する
//   アプリケーションの State を担う
// --------------------------------------------------

const ModelRecord = Record(initialStateObj);

export class Model extends ModelRecord {

  constructor() {

    const map = fromJSOrdered(initialStateObj);

    // console.log('map = ', map.toJS());

    super(map);

  }



  /**
   * 通知を切り替える
   * @param {number} unreadTotal      未読数
   * @param {array}  unreadArr        未読の配列
   * @param {number} alreadyReadTotal 既読数
   * @param {array}  alreadyReadArr   既読の配列
   * @param {number} activePage       アクティブページ
   */
  setNotificationMap(unreadTotal, unreadArr, alreadyReadTotal, alreadyReadArr, activePage) {

    // console.log('setModalNotificationShow');
    // console.log('unreadTotal = ', unreadTotal);
    // console.log('unreadArr = ', unreadArr);
    // console.log('alreadyReadTotal = ', alreadyReadTotal);
    // console.log('alreadyReadArr = ', alreadyReadArr);

    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Notification
    // --------------------------------------------------

    if (unreadArr) {
      map = map.setIn(['notificationMap', 'activeType'], 'unread');
      map = map.setIn(['notificationMap', 'unreadTotal'], unreadTotal);
      map = map.setIn(['notificationMap', 'unreadArr'], fromJSOrdered(unreadArr));
      map = map.setIn(['notificationMap', 'unreadActivePage'], activePage);
    }

    if (alreadyReadArr) {
      map = map.setIn(['notificationMap', 'activeType'], 'alreadyRead');
      map = map.setIn(['notificationMap', 'alreadyReadTotal'], alreadyReadTotal);
      map = map.setIn(['notificationMap', 'alreadyReadArr'], fromJSOrdered(alreadyReadArr));
      map = map.setIn(['notificationMap', 'alreadyReadActivePage'], activePage);
    }

    // console.log('setSelectNotification map = ', map.toJS());

    return map;

  }


}

export default Model;
