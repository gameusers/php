// --------------------------------------------------
//   Import
// --------------------------------------------------

import { Record } from 'immutable';
import { fromJSOrdered } from '../../../../js/models/model';



const initialStateObj = {};


// --------------------------------------------------
//   モーダル
// --------------------------------------------------

initialStateObj.testMap = {
  aaa: {
    bbb: false
  }
};



// --------------------------------------------------
//   ヘッダー / メニュー
//   新ページ・新コンテンツを追加する場合はここを編集すること
// --------------------------------------------------

// initialStateObj.headerMap.menuMap = {
//
//   app: {
//     'share-buttons': {
//       urlDirectory1: 'app',
//       urlDirectory2: 'share-buttons',
//       activeUrlDirectory3: null,
//       text: 'シェアボタン'
//     },
//     pay: {
//       urlDirectory1: 'app',
//       urlDirectory2: 'pay',
//       activeUrlDirectory3: null,
//       text: '購入'
//     }
//   }
//
// };


// --------------------------------------------------
//   メイン / メニュー
//   新ページ・新コンテンツを追加する場合はここを編集すること
//   Material Icons はこちらから選択 / https://material.io/icons/
//   <i class="material-icons">assignment_ind</i>
//   ICON FONT のタグの中身を入力すること
// --------------------------------------------------

// initialStateObj.menuMap = {
//
//   app: {
//     'share-buttons': [
//       {
//         urlDirectory1: 'app',
//         urlDirectory2: 'share-buttons',
//         urlDirectory3: null,
//         materialIcon: 'share',
//         text: 'シェアボタン'
//       },
//     ],
//     pay: [
//       {
//         urlDirectory1: 'app',
//         urlDirectory2: 'pay',
//         urlDirectory3: null,
//         materialIcon: 'payment',
//         text: '購入'
//       },
//       {
//         urlDirectory1: 'app',
//         urlDirectory2: 'pay',
//         urlDirectory3: 'info',
//         materialIcon: 'announcement',
//         text: '特定商取引法に基づく表記'
//       },
//     ]
//   },
//
//   drawerActive: false
//
// };



// --------------------------------------------------
//   コンテンツ
// --------------------------------------------------

initialStateObj.contentsMap = {};


// --------------------------------------------------
//   コンテンツ / アプリ
// --------------------------------------------------

initialStateObj.contentsMap.appMap = {

  payMap: {
    formShareButtonsMap: {
      webSiteNameMap: {
        value: 'AAA',
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
      }
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

export class Model extends ModelRecord {

  constructor() {

    const map = fromJSOrdered(initialStateObj);

    // console.log('map = ', map.toJS());

    super(map);

  }

}

export default Model;
