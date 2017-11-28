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

  shareButtonsMap: {
    recruitmentMap: {
      formMap: {
        authorNameMap: {
          value: '',
          validationState: null,
          required: true,
          error: true
        },
        authorUrlMap: {
          value: '',
          validationState: null,
          required: false,
          error: false
        },
        fileMap: {
          value: '',
          validationState: null,
          required: true,
          error: true
        },
        mailMap: {
          value: '',
          validationState: null,
          required: true,
          error: true
        },
        webSiteNameMap: {
          value: '',
          validationState: null,
          required: false,
          error: false
        },
        webSiteUrlMap: {
          value: '',
          validationState: null,
          required: false,
          error: false
        },
        commentMap: {
          value: '',
          validationState: null,
          required: false,
          error: false
        },
        agreementMap: {
          value: false,
          validationState: null,
          required: true,
          error: true
        },
      },
    },
    campaignMap: {
      formMap: {
        blogNameMap: {
          value: '',
          validationState: null,
          required: true,
          error: true
        },
        blogUrlMap: {
          value: '',
          validationState: null,
          required: true,
          error: true
        },
        articleUrlMap: {
          value: '',
          validationState: null,
          required: true,
          error: true
        },
        mailMap: {
          value: '',
          validationState: null,
          required: true,
          error: true
        },
        commentMap: {
          value: '',
          validationState: null,
          required: false,
          error: false
        },
        agreementMap: {
          value: false,
          validationState: null,
          required: true,
          error: true
        },
      },
    },
  },
  payMap: {
    formShareButtonsMap: {
      webSiteNameMap: {
        value: '',
        validationState: null,
        required: true,
        error: true
      },
      webSiteUrlMap: {
        value: '',
        validationState: null,
        required: true,
        error: true
      },
      agreementMap: {
        value: false,
        validationState: null,
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



  // --------------------------------------------------
  //   シェアボタン / テーマ募集
  // --------------------------------------------------

  /**
   * テーマ募集 / 作者名
   * @param {string} value 作者名
   */
  setContentsAppShareButtonsRecruitmentFormAuthorName(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Get Required
    // --------------------------------------------------

    const required = map.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorNameMap', 'required']);


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (required && !value) {

      validationState = 'error';
      error = true;

    } else if (!required && !value) {

      validationState = null;
      error = false;

    } else if (value.length <= 255) {

      validationState = 'success';
      error = false;

    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorNameMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorNameMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorNameMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }


  /**
   * テーマ募集 / 作者ウェブサイトのURL
   * @param {string} value 作者ウェブサイトのURL
   */
  setContentsAppShareButtonsRecruitmentFormAuthorUrl(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Get Required
    // --------------------------------------------------

    const required = map.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorUrlMap', 'required']);


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (required && !value) {

      validationState = 'error';
      error = true;

    } else if (!required && !value) {

      validationState = null;
      error = false;

    } else if (value.length <= 255 && value.match(/^(https?)(:\/\/[-_.!~*'()a-zA-Z0-9;/?:@&=+$,%#]+)$/)) {

      validationState = 'success';
      error = false;

    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorUrlMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorUrlMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorUrlMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }


  /**
   * テーマ募集 / テーマアップロード
   * @param {string} value テーマアップロード
   */
  setContentsAppShareButtonsRecruitmentFormFile(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Get Required
    // --------------------------------------------------

    const required = map.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'fileMap', 'required']);


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (required && !value) {

      validationState = 'error';
      error = true;

    } else if (!required && !value) {

      validationState = null;
      error = false;

    } else if (value.type === 'application/x-zip-compressed') {

      validationState = 'success';
      error = false;

    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'fileMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'fileMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'fileMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }


  /**
   * テーマ募集 / メールアドレス（非公開）
   * @param {string} value メールアドレス（非公開）
   */
  setContentsAppShareButtonsRecruitmentFormMail(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Get Required
    // --------------------------------------------------

    const required = map.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'mailMap', 'required']);


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (required && !value) {

      validationState = 'error';
      error = true;

    } else if (!required && !value) {

      validationState = null;
      error = false;

    } else if (value.length <= 255 && value.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/)) {

      validationState = 'success';
      error = false;

    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'mailMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'mailMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'mailMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }


  /**
   * テーマ募集 / ウェブサイトの名前（非公開）
   * @param {string} value ウェブサイトの名前（非公開）
   */
  setContentsAppShareButtonsRecruitmentFormWebSiteName(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Get Required
    // --------------------------------------------------

    const required = map.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteNameMap', 'required']);


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (required && !value) {

      validationState = 'error';
      error = true;

    } else if (!required && !value) {

      validationState = null;
      error = false;

    } else if (value.length <= 255) {

      validationState = 'success';
      error = false;

    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteNameMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteNameMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteNameMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }


  /**
   * テーマ募集 / 作者ウェブサイトのURL
   * @param {string} value 作者ウェブサイトのURL
   */
  setContentsAppShareButtonsRecruitmentFormWebSiteUrl(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Get Required
    // --------------------------------------------------

    const required = map.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteUrlMap', 'required']);


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (required && !value) {

      validationState = 'error';
      error = true;

    } else if (!required && !value) {

      validationState = null;
      error = false;

    } else if (value.length <= 255 && value.match(/^(https?)(:\/\/[-_.!~*'()a-zA-Z0-9;/?:@&=+$,%#]+)$/)) {

      validationState = 'success';
      error = false;

    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteUrlMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteUrlMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteUrlMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }


  /**
   * テーマ募集 / コメント（非公開）
   * @param {string} value コメント（非公開）
   */
  setContentsAppShareButtonsRecruitmentFormComment(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Get Required
    // --------------------------------------------------

    const required = map.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'commentMap', 'required']);


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (required && !value) {

      validationState = 'error';
      error = true;

    } else if (!required && !value) {

      validationState = null;
      error = false;

    } else if (value.length <= 2000) {

      validationState = 'success';
      error = false;

    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'commentMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'commentMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'commentMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }


  /**
   * テーマ募集 / 「テーマ応募時の了承事項」を読んで了承しました
   * @param {string} value true / false
   */
  setContentsAppShareButtonsRecruitmentFormAgreement(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (value) {
      validationState = 'success';
      error = false;
    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'agreementMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'agreementMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'agreementMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }



  // --------------------------------------------------
  //   シェアボタン / キャンペーン
  // --------------------------------------------------

  /**
   * キャンペーン / ブログの名前
   * @param {string} value ブログの名前
   */
  setContentsAppShareButtonsCampaignFormBlogName(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Get Required
    // --------------------------------------------------

    const required = map.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogNameMap', 'required']);


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (required && !value) {

      validationState = 'error';
      error = true;

    } else if (!required && !value) {

      validationState = null;
      error = false;

    } else if (value.length <= 255) {

      validationState = 'success';
      error = false;

    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogNameMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogNameMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogNameMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }


  /**
   * キャンペーン / ブログのURL
   * @param {string} value ブログのURL
   */
  setContentsAppShareButtonsCampaignFormBlogUrl(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Get Required
    // --------------------------------------------------

    const required = map.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogUrlMap', 'required']);


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (required && !value) {

      validationState = 'error';
      error = true;

    } else if (!required && !value) {

      validationState = null;
      error = false;

    } else if (value.length <= 255 && value.match(/^(https?)(:\/\/[-_.!~*'()a-zA-Z0-9;/?:@&=+$,%#]+)$/)) {

      validationState = 'success';
      error = false;

    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogUrlMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogUrlMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogUrlMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }


  /**
   * キャンペーン / 記事のURL
   * @param {string} value 記事のURL
   */
  setContentsAppShareButtonsCampaignFormArticleUrl(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Get Required
    // --------------------------------------------------

    const required = map.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'articleUrlMap', 'required']);


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (required && !value) {

      validationState = 'error';
      error = true;

    } else if (!required && !value) {

      validationState = null;
      error = false;

    } else if (value.length <= 255 && value.match(/^(https?)(:\/\/[-_.!~*'()a-zA-Z0-9;/?:@&=+$,%#]+)$/)) {

      validationState = 'success';
      error = false;

    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'articleUrlMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'articleUrlMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'articleUrlMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }


  /**
   * キャンペーン / メールアドレス
   * @param {string} value メールアドレス
   */
  setContentsAppShareButtonsCampaignFormMail(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Get Required
    // --------------------------------------------------

    const required = map.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'mailMap', 'required']);


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (required && !value) {

      validationState = 'error';
      error = true;

    } else if (!required && !value) {

      validationState = null;
      error = false;

    } else if (value.length <= 255 && value.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/)) {

      validationState = 'success';
      error = false;

    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'mailMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'mailMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'mailMap', 'error'], error);


    // console.log('setContentsAppShareButtonsCampaignFormMail map = ', map.toJS());

    return map;

  }


  /**
   * キャンペーン / コメント
   * @param {string} value コメント
   */
  setContentsAppShareButtonsCampaignFormComment(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Get Required
    // --------------------------------------------------

    const required = map.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'commentMap', 'required']);


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (required && !value) {

      validationState = 'error';
      error = true;

    } else if (!required && !value) {

      validationState = null;
      error = false;

    } else if (value.length <= 2000) {

      validationState = 'success';
      error = false;

    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'commentMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'commentMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'commentMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }


  /**
   * キャンペーン / 「キャンペーン対象外のブログについて」を読んで了承しました
   * @param {string} value true / false
   */
  setContentsAppShareButtonsCampaignFormAgreement(value) {


    // --------------------------------------------------
    //   Copy State
    // --------------------------------------------------

    let map = this;


    // --------------------------------------------------
    //   Validation State & Error
    // --------------------------------------------------

    let validationState = 'error';
    let error = true;

    if (value) {
      validationState = 'success';
      error = false;
    }


    // --------------------------------------------------
    //   Set Value
    // --------------------------------------------------

    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'agreementMap', 'value'], value);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'agreementMap', 'validationState'], validationState);
    map = map.setIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'agreementMap', 'error'], error);


    // console.log('setContentsAppPayFormShareButtonsWebSiteUrl map = ', map.toJS());

    return map;

  }





  // --------------------------------------------------
  //   購入
  // --------------------------------------------------

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
