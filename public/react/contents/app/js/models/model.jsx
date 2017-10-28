// --------------------------------------------------
//   Import
// --------------------------------------------------

import { Record } from 'immutable';
import { fromJSOrdered } from '../../../../js/modules/package';



// --------------------------------------------------
//   初期ステート
// --------------------------------------------------

const initialStateObj = {};
initialStateObj.contentsMap = {};



// --------------------------------------------------
//   コンテンツ / アプリ
// --------------------------------------------------

initialStateObj.contentsMap.appMap = {

  payMap: {
    formShareButtonsMap: {
      webSiteNameMap: {
        value: '',
        validationState: 'error',
        required: true,
        error: true
      },
      webSiteUrlMap: {
        value: '',
        validationState: 'error',
        required: true,
        error: true
      },
      agreementMap: {
        value: false,
        validationState: 'error',
        required: true,
        error: true
      },
      purchased: false
    },
    shareButtonsWebSiteName: '',
    shareButtonsWebSiteUrl: '',
    shareButtonsAgreement: false
  }

};



// console.log('initialStateObj = ', initialStateObj);
// console.log('Cookies.get() = ', Cookies.get());




// --------------------------------------------------
//   Class Model
//   Immutable.js の Reacord クラスを継承して Model クラスを作成する
//   アプリケーションの State を担う
// --------------------------------------------------

const ModelRecord = Record(initialStateObj);

export class ModelApp extends ModelRecord {

  constructor() {

    const map = fromJSOrdered(initialStateObj);

    // console.log('map = ', map.toJS());

    super(map);

  }



  /**
   * ウェブサイトの名前
   * @param {string} webSiteName ウェブサイトの名前
   */
  setContentsAppPayFormShareButtonsWebSiteName(webSiteName) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (webSiteName !== '' && webSiteName.length <= 100) {
      validationState = 'success';
      error = false;
    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteNameMap', 'value'], webSiteName);
    map = map.setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteNameMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteNameMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteName');
    // console.log('webSiteName = ', webSiteName);
    // console.log('validationState = ', validationState);
    // console.log('error = ', error);
    // console.log('setContentsAppPayFormShareButtonsWebSiteName map = ', map.toJS());

    return map;

  }



  /**
   * ウェブサイトのURL
   * @param {string} webSiteUrl ウェブサイトのURL
   */
  setContentsAppPayFormShareButtonsWebSiteUrl(webSiteUrl) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (webSiteUrl.match(/^(https?)(:\/\/[-_.!~*'()a-zA-Z0-9;/?:@&=+$,%#]+)$/)) {
      validationState = 'success';
      error = false;
    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteUrlMap', 'value'], webSiteUrl);
    map = map.setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteUrlMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteUrlMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl');
    // console.log('webSiteUrl = ', webSiteUrl);
    // console.log('validationState = ', validationState);
    // console.log('error = ', error);
    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }



  /**
   * 「有料プランの注意事項」を読んで了承しましたチェックボックス
   * @param {boolean} agreement 注意事項の了承
   */
  setContentsAppPayFormShareButtonsAgreement(agreement) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (agreement) {
      validationState = 'success';
      error = false;
    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'agreementMap', 'value'], agreement);
    map = map.setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'agreementMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'agreementMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsAgreement');
    // console.log('agreement = ', agreement);
    // console.log('validationState = ', validationState);
    // console.log('error = ', error);
    // console.log('setContentsAppPayFormShareButtonsAgreement map = ', map.toJS());

    return map;

  }



}

export default ModelApp;
