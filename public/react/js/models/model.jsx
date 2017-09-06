// --------------------------------------------------
//   Import
// --------------------------------------------------

import { fromJS, Map, OrderedMap, Seq, Record } from 'immutable';
import Cookies from 'js-cookie';



// --------------------------------------------------
//   Constant
// --------------------------------------------------

// export const GAMEUSERS_API_URL = 'https://localhost/gameusers/public/rest/api/public.json';
// export const THEME_DESIGN_URL = 'https://localhost/gameusers/public/dev/blog/wp-content/plugins/gameusers-share-buttons/themes-design';
// export const THEME_ICON_URL = 'https://localhost/gameusers/public/dev/blog/wp-content/plugins/gameusers-share-buttons/themes-icon';
// 'https://gameusers.org/app/share-buttons/themes/';
// 'https://gameusers.org/app/share-buttons/icon-themes/';






// --------------------------------------------------
//   initial State 取得（サーバーから受け取る初期データ）
// --------------------------------------------------

const initialStateObj = gameUsersInitialStateObj();


// --------------------------------------------------
//   BrowserRouter の basename（react-router-dom の記述で利用する）
//   開発環境の場合、正しくアクセスできるリンクにするために基本のURLを設定する必要がある
// --------------------------------------------------

initialStateObj.baseName = '/gameusers/public';

if (location.hostname === 'gameusers.org') {
  initialStateObj.baseName = '';
}


// --------------------------------------------------
//   URL Directory パスを分解したもの
//   現在どの場所にいて、どのコンテンツを表示するかの判定に利用する
//   https://gameusers.org/urlDirectory1/urlDirectory2/urlDirectory3
// --------------------------------------------------

let tempArr = location.href.split(initialStateObj.urlBase);
tempArr = tempArr[1].split('/');

initialStateObj.urlDirectory1 = null;
initialStateObj.urlDirectory2 = null;
initialStateObj.urlDirectory3 = null;

if (tempArr[0]) {
  initialStateObj.urlDirectory1 = tempArr[0];
}

if (tempArr[1]) {
  initialStateObj.urlDirectory2 = tempArr[1];
}

if (tempArr[2]) {
  initialStateObj.urlDirectory3 = tempArr[2];
}


// --------------------------------------------------
//   通知
// --------------------------------------------------

initialStateObj.notificationObj.activeType = 'unread';
initialStateObj.notificationObj.unreadTotal = 10;
initialStateObj.notificationObj.unreadArr = [];
initialStateObj.notificationObj.unreadActivePage = 1;
initialStateObj.notificationObj.alreadyReadTotal = 10;
initialStateObj.notificationObj.alreadyReadArr = [];
initialStateObj.notificationObj.alreadyReadActivePage = 1;



// --------------------------------------------------
//   フッターに表示するカードの種類を指定
//   gameCommunityRenewal / 最近更新されたゲームコミュニティ
//   gameCommunityAccess / 最近アクセスしたゲームコミュニティ
//   userCommunityAccess / 最近アクセスしたユーザーコミュニティ
// --------------------------------------------------

if (initialStateObj.footerObj.gameCommunityAccessArr) {
  initialStateObj.footerObj.cardType = 'gameCommunityAccess';
} else if (initialStateObj.footerObj.userCommunityAccessArr) {
  initialStateObj.footerObj.cardType = 'userCommunityAccess';
} else {
  initialStateObj.footerObj.cardType = 'gameCommunityRenewal';
}



// --------------------------------------------------
//   モーダル
// --------------------------------------------------

initialStateObj.modalObj = {
  notification: {
    show: false
  }
};


console.log('initialStateObj = ', initialStateObj);
console.log('Cookies.get() = ', Cookies.get());





// --------------------------------------------------
//   Immutable fromJSOrdered
//   Immutable.js ではオブジェクトの並び順を維持したまま
//   Immutable.js の Map型に変換する関数がないため、オリジナルで作成
// --------------------------------------------------

export const fromJSOrdered = (data) => {

  if (typeof data !== 'object' || data === null) {
    return data;
  }

  if (Array.isArray(data)) {
    return Seq(data).map(fromJSOrdered).toList();
  }

  return Seq(data).map(fromJSOrdered).toOrderedMap();

};



// --------------------------------------------------
//   Class Model
// --------------------------------------------------

const ModelRecord = Record(initialStateObj);

export class Model extends ModelRecord {

  constructor() {

    const map = fromJSOrdered(initialStateObj);
    // const map = fromJS(initialStateObj);



    // console.log()

    // console.log('map = ', map.toJS());

    super(map);

  }



  /**
   * 通知を切り替える
   * @param {number} unreadTotal      [description]
   * @param {array} unreadArr        [description]
   * @param {number} alreadyReadTotal [description]
   * @param {array} alreadyReadArr   [description]
   * @param {number} activePage       [description]
   */
  setNotificationObj(unreadTotal, unreadArr, alreadyReadTotal, alreadyReadArr, activePage) {

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
      map = map.setIn(['notificationObj', 'activeType'], 'unread');
      map = map.setIn(['notificationObj', 'unreadTotal'], unreadTotal);
      map = map.setIn(['notificationObj', 'unreadArr'], fromJSOrdered(unreadArr));
      map = map.setIn(['notificationObj', 'unreadActivePage'], activePage);
    }

    if (alreadyReadArr) {
      map = map.setIn(['notificationObj', 'activeType'], 'alreadyRead');
      map = map.setIn(['notificationObj', 'alreadyReadTotal'], alreadyReadTotal);
      map = map.setIn(['notificationObj', 'alreadyReadArr'], fromJSOrdered(alreadyReadArr));
      map = map.setIn(['notificationObj', 'alreadyReadActivePage'], activePage);
    }

    // console.log('setSelectNotification map = ', map.toJS());

    return map;

  }


}

// export default Model;
