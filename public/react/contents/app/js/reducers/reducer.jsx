// --------------------------------------------------
//   Import
// --------------------------------------------------

import { ModelApp } from '../models/model';



const reducerApp = (state = new ModelApp(), action) => {


  switch (action.type) {


    // --------------------------------------------------
    //   コンテンツ / アプリ / 購入
    // --------------------------------------------------

    case 'CONTENTS_APP_PAY_FORM_SHARE_BUTTONS_WEB_SITE_NAME': {
      return state.setContentsAppPayFormShareButtonsWebSiteName(action.value);
    }

    case 'CONTENTS_APP_PAY_FORM_SHARE_BUTTONS_WEB_SITE_URL': {
      return state.setContentsAppPayFormShareButtonsWebSiteUrl(action.value);
    }

    case 'CONTENTS_APP_PAY_FORM_SHARE_BUTTONS_AGREEMENT': {
      return state.setContentsAppPayFormShareButtonsAgreement(action.value);
    }

    case 'CONTENTS_APP_PAY_FORM_SHARE_BUTTONS_PURCHASED': {
      return state.setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'purchased'], action.value);
    }


    default: {
      return state;
    }

  }

};



export default reducerApp;
