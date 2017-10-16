// --------------------------------------------------
//   Import
// --------------------------------------------------

import { Model } from '../models/model';



const reducerApp = (state = new Model(), action) => {


  switch (action.type) {


    // --------------------------------------------------
    //   コンテンツ / アプリ / 購入
    // --------------------------------------------------

    case 'CONTENTS_APP_PAY_FORM_SHARE_BUTTONS_WEB_SITE_NAME': {
      return state.setContentsAppPayFormShareButtonsWebSiteName(action.value);
      // return state.setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteNameMap', 'value'], action.value);
    }

    case 'CONTENTS_APP_PAY_FORM_SHARE_BUTTONS_WEB_SITE_URL': {
      return state.setContentsAppPayFormShareButtonsWebSiteUrl(action.value);
      // return state.setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteUrlMap', 'value'], action.value);
    }

    case 'CONTENTS_APP_PAY_FORM_SHARE_BUTTONS_AGREEMENT': {
      return state.setContentsAppPayFormShareButtonsAgreement(action.value);
      // let validationState = 'error';
      // let error = true;
      //
      // if (action.value) {
      //   validationState = 'success';
      //   error = false;
      // }
      //
      // return state
      //   .setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'agreementMap', 'value'], action.value)
      //   .setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'agreementMap', 'validationState'], validationState)
      //   .setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'agreementMap', 'error'], error);
    }


    default: {
      return state;
    }

  }

};



export default reducerApp;
