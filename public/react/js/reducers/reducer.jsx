// --------------------------------------------------
//   Import
// --------------------------------------------------

import { Model } from '../models/model';
import { fromJSOrdered } from '../modules/package';



const reducerRoot = (state = new Model(), action) => {


  switch (action.type) {


    // --------------------------------------------------
    //   共通
    // --------------------------------------------------

    case 'URL_DIRECTORY': {
      return state
        .setIn(['menuMap', 'drawerActive'], false)
        .set('urlDirectory1', action.urlDirectory1)
        .set('urlDirectory2', action.urlDirectory2)
        .set('urlDirectory3', action.urlDirectory3)
        .setIn(['menuMap', 'headerMap', action.urlDirectory1, action.urlDirectory2, 'activeUrlDirectory3'], action.urlDirectory3);
    }



    // --------------------------------------------------
    //   モーダル
    // --------------------------------------------------

    case 'MODAL_MAP_NOTIFICATION_SHOW': {
      return state.setIn(['modalMap', 'notification', 'show'], action.value);
    }



    // --------------------------------------------------
    //   通知
    // --------------------------------------------------

    case 'NOTIFICATION_MAP_UNREAD_COUNT': {
      return state.setIn(['notificationMap', 'unreadCount'], action.value);
    }


    case 'NOTIFICATION_MAP_RESET_ACTIVE_PAGE': {
      return state
        .setIn(['notificationMap', 'unreadActivePage'], 1)
        .setIn(['notificationMap', 'alreadyReadActivePage'], 1);
    }


    case 'NOTIFICATION_MAP': {
      return state.setNotificationMap(action.unreadTotal, action.unreadArr, action.alreadyReadTotal, action.alreadyReadArr, action.activePage);
    }



    // --------------------------------------------------
    //   ドロワーメニュー / モバイル用
    //   boolean のトグルになっている
    // --------------------------------------------------

    case 'MENU_DRAWER_ACTIVE': {

      let menuDrawerActive = true;

      if (state.getIn(['menuMap', 'drawerActive'])) {
        menuDrawerActive = false;
      }

      // console.log('reducer / DRAWER_MENU_ACTIVE = ', menuDrawerActive);

      return state.setIn(['menuMap', 'drawerActive'], menuDrawerActive);

    }



    // --------------------------------------------------
    //   フッター
    // --------------------------------------------------

    case 'FOOTER_CARD_TYPE': {

      const footerMap = {
        cardType: action.cardType,
        gameCommunityRenewalList: action.gameCommunityRenewalList,
        gameCommunityAccessList: action.gameCommunityAccessList,
        userCommunityAccessList: action.userCommunityAccessList
      };

      return state.set('footerMap', fromJSOrdered(footerMap));

    }








    default: {
      return state;
    }

  }

};



export default reducerRoot;
