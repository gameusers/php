// --------------------------------------------------
//   Import
// --------------------------------------------------

import { List } from 'immutable';
import { Model, fromJSOrdered } from '../models/model';



const reducer = (state = new Model(), action) => {

  // const currentThemeNameId = state.getIn(['formObj', 'currentThemeNameId']);
  // const currentThemeType = state.getIn(['formObj', 'currentThemeType']);
  // const shareType = state.getIn(['formObj', 'shareType']);

  switch (action.type) {

    // case 'INITIAL_ASYNCHRONOUS': {
    //   return state
    //     .set('designThemesMap', action.designThemesMap)
    //     .set('iconThemesMap', action.iconThemesMap)
    //     .setDataObj('editThemes', action.loadedDataEditThemesObj)
    //     .setDataObj('designThemes', action.loadedDataDesignThemesObj)
    //     .setDataObj('iconThemes', action.loadedDataIconThemesObj)
    //     .set('randomDesignThemesList', List(action.randomDesignThemesArr))
    //     .set('randomIconThemesList', List(action.randomIconThemesArr));
    // }



    // --------------------------------------------------
    //   共通
    // --------------------------------------------------

    case 'URL_DIRECTORY': {
      return state
        .set('urlDirectory1', action.urlDirectory1)
        .set('urlDirectory2', action.urlDirectory2)
        .set('urlDirectory3', action.urlDirectory3);
    }


    // --------------------------------------------------
    //   通知
    // --------------------------------------------------

    case 'NOTIFICATION_UNREAD_COUNT': {
      return state.setIn(['notificationObj', 'unreadCount'], action.value);
    }


    // --------------------------------------------------
    //   フッター
    // --------------------------------------------------

    case 'FOOTER_CARD_TYPE': {

      const footerObj = {
        cardType: action.cardType,
        gameCommunityRenewalArr: action.gameCommunityRenewalArr,
        gameCommunityAccessArr: action.gameCommunityAccessArr,
        userCommunityAccessArr: action.userCommunityAccessArr
      };

      return state.set('footerObj', fromJSOrdered(footerObj));

    }



    // --------------------------------------------------
    //   モーダル
    // --------------------------------------------------

    case 'MODAL_NOTIFICATION_SHOW': {
      return state.setIn(['modalObj', 'notification', 'show'], action.value);
    }



    default: {
      return state;
    }

  }

};



export default reducer;
