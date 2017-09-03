// --------------------------------------------------
//   Import
// --------------------------------------------------

import { fromJS, Map, OrderedMap, Seq, Record } from 'immutable';



// --------------------------------------------------
//   Constant
// --------------------------------------------------

// export const GAMEUSERS_API_URL = 'https://localhost/gameusers/public/rest/api/public.json';
// export const THEME_DESIGN_URL = 'https://localhost/gameusers/public/dev/blog/wp-content/plugins/gameusers-share-buttons/themes-design';
// export const THEME_ICON_URL = 'https://localhost/gameusers/public/dev/blog/wp-content/plugins/gameusers-share-buttons/themes-icon';
// 'https://gameusers.org/app/share-buttons/themes/';
// 'https://gameusers.org/app/share-buttons/icon-themes/';



// --------------------------------------------------
//   initial State
// --------------------------------------------------

// const initialStateObj = {
//
//   formObj: {
//     toggleEditForm: false,
//     checkStickySampleTheme: true,
//     currentThemeNameId: 'new-theme',
//     currentThemeType: 'type1',
//     optionType: '',
//     shareType: '',
//     shareImageAspectRatioFixed: true,
//     freeImageAspectRatioFixed: true,
//     freeUploadImageAspectRatioFixed: true,
//     countInput: '',
//     countInputMin: 0,
//     countInputMax: 9999,
//     countBackgroundColor: false,
//     countBackgroundColorHex: '',
//     checkDownloadThemesList: []
//   },
//
//   dataEditThemesObj: {},
//   dataDesignThemesObj: {},
//   dataIconThemesObj: {},
//
//   uploadImageObj: {},
//   googleFontsArr: [],
//
//   editThemesMap: {},
//   designThemesMap: {},
//   iconThemesMap: {},
//
//   contentsNumberOfLines: 20,
//   editThemesPage: 1,
//   designThemesPage: 1,
//   iconThemesPage: 1,
//
//   randomDesignThemesList: [],
//   randomIconThemesList: [],
//
// };

// JSON.parse();

const initialStateObj = gameUsersInitialStateObj();

console.log('initialStateObj = ', initialStateObj);

console.log('initialStateObj = ', Object.prototype.toString.call(gameUsersInitialStateObj()));


// --------------------------------------------------
//   Immutable fromJSOrdered
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
//   Set Option
// --------------------------------------------------

// const optionJsonObj = {
//   php: optionObj.php,
//   twitterApiType: optionObj.twitterApiType,
//   rssUrl: optionObj.rssUrl
// };
//
// instanceGameUsersShareButtonsOption.setOptionJsonObj(optionJsonObj);



// --------------------------------------------------
//   Class Model
// --------------------------------------------------

const ModelRecord = Record(initialStateObj);

export class Model extends ModelRecord {

  constructor() {

    const map = fromJS(initialStateObj);

    // if (Object.prototype.toString.call(optionObj.editThemesArr) === '[object Array]') {
    //   map = map.set('editThemesMap', fromJSOrdered({}));
    // } else {
    //   map = map.set('editThemesMap', fromJSOrdered(optionObj.editThemesArr));
    // }

    super(map);

  }



  // setToggleEditForm(themeNameId) {
  //
  //   let map = this;
  //
  //   // console.log('setToggleEditForm / map = ', map.toJS());
  //
  //   // --------------------------------------------------
  //   //   ToggleEditForm
  //   // --------------------------------------------------
  //
  //   const currentThemeNameId = map.getIn(['formObj', 'currentThemeNameId']);
  //   const toggleEditForm = map.getIn(['formObj', 'toggleEditForm']);
  //
  //   if (!themeNameId) {
  //     return map.setIn(['formObj', 'toggleEditForm'], false);
  //   }
  //
  //   if (toggleEditForm && themeNameId === currentThemeNameId) {
  //     return map.setIn(['formObj', 'toggleEditForm'], false);
  //   }
  //
  //   map = map.setIn(['formObj', 'toggleEditForm'], true);
  //
  //
  //   // --------------------------------------------------
  //   //   CurrentThemeNameId
  //   // --------------------------------------------------
  //
  //   map = map.setIn(['formObj', 'currentThemeNameId'], themeNameId);
  //
  //
  //   // --------------------------------------------------
  //   //   Data Edit Object
  //   // --------------------------------------------------
  //
  //   // console.log('themeNameId = ', themeNameId);
  //   // console.log('dataSampleThemesObj = ', map.getIn(['dataSampleThemesObj', themeNameId]).toJS());
  //   // console.log('dataEditThemesObj = ', map.getIn(['dataEditThemesObj', themeNameId]).toJS());
  //
  //   if (!map.hasIn(['dataSampleThemesObj', themeNameId])) {
  //     const dataEditThemesObj = map.getIn(['dataEditThemesObj', themeNameId]);
  //     map = map.setIn(['dataSampleThemesObj', themeNameId], dataEditThemesObj);
  //
  //     // console.log('dataEditThemesObj = ', dataEditThemesObj.toJS());
  //   }
  //   // console.log('dataSampleThemesObj = ', dataSampleThemesObj);
  //
  //
  //   // --------------------------------------------------
  //   //   Data Obj openedThemeType
  //   // --------------------------------------------------
  //
  //   const openedThemeType = map.getIn(['dataSampleThemesObj', themeNameId, 'openedThemeType']);
  //   map = map.setIn(['formObj', 'currentThemeType'], openedThemeType);
  //
  //
  //   // --------------------------------------------------
  //   //   Share Type
  //   // --------------------------------------------------
  //
  //   const currentThemeType = map.getIn(['formObj', 'currentThemeType']);
  //   const share = map.getIn(['dataSampleThemesObj', themeNameId, currentThemeType, 'share']);
  //
  //   if (share.count() > 0) {
  //     map = map.setIn(['formObj', 'shareType'], share.keySeq().first());
  //   } else {
  //     map = map.setIn(['formObj', 'shareType'], '');
  //   }
  //
  //   // console.log('setToggleEditForm map = ', map.toJS());
  //
  //   return map;
  //
  // }
  //
  //
  // setShareImage(file, src, width, height, extension) {
  //   // console.log('setShareImage');
  //
  //   let map = this;
  //
  //   const currentThemeNameId = map.getIn(['formObj', 'currentThemeNameId']);
  //   const currentThemeType = map.getIn(['formObj', 'currentThemeType']);
  //
  //   const shareImageWidth = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageWidth']);
  //   const shareImageHeight = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageHeight']);
  //
  //   const selectedIndex = document.querySelector('#share-image-type').selectedIndex;
  //   const shareImageType = document.querySelector('#share-image-type').options[selectedIndex].value;
  //
  //
  //   map = map.setIn(['uploadImageObj', currentThemeNameId, currentThemeType, shareImageType, 'file'], file);
  //   map = map.setIn(['uploadImageObj', currentThemeNameId, currentThemeType, shareImageType, 'src'], src);
  //
  //   map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageDefaultWidth'], width);
  //   map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageDefaultHeight'], height);
  //
  //   if (shareImageWidth === '' && shareImageHeight === '') {
  //     map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageWidth'], width);
  //     map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageHeight'], height);
  //   }
  //
  //   map = map.setIn(['formObj', 'shareType'], shareImageType);
  //
  //
  //   if (map.hasIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'share', shareImageType])) {
  //     map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'share', shareImageType, 'extension'], extension);
  //   } else {
  //     map = map.setIn(
  //       ['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'share', shareImageType],
  //       Map({ button: true, count: true, extension, countDefaultText: '', countMin: '', countMax: '', })
  //     );
  //   }
  //
  //   // console.log('last Map =', map.toJS());
  //
  //   return map;
  //
  // }
  //
  // setShareImageWidth(width) {
  //
  //   let map = this;
  //
  //   const currentThemeNameId = map.getIn(['formObj', 'currentThemeNameId']);
  //   const currentThemeType = map.getIn(['formObj', 'currentThemeType']);
  //   const shareImageAspectRatioFixed = map.getIn(['formObj', 'shareImageAspectRatioFixed']);
  //
  //   const shareImageDefaultWidth = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageDefaultWidth']);
  //   const shareImageDefaultHeight = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageDefaultHeight']);
  //
  //
  //   const widthNumber = width ? parseInt(width, 10) : '';
  //
  //
  //   map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageWidth'], widthNumber);
  //
  //   if (shareImageAspectRatioFixed) {
  //
  //     if (widthNumber === '') {
  //       map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageHeight'], '');
  //     } else {
  //       const height = Math.round(widthNumber * (shareImageDefaultHeight / shareImageDefaultWidth));
  //       map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageHeight'], height);
  //     }
  //
  //   }
  //
  //   // console.log('shareImageAspectRatioFixed = ', shareImageAspectRatioFixed);
  //   // console.log('shareImageDefaultWidth = ', shareImageDefaultWidth);
  //   // console.log('shareImageDefaultHeight = ', shareImageDefaultHeight);
  //
  //
  //   return map;
  //
  // }
  //
  // setShareImageHeight(height) {
  //
  //   let map = this;
  //
  //   const currentThemeNameId = map.getIn(['formObj', 'currentThemeNameId']);
  //   const currentThemeType = map.getIn(['formObj', 'currentThemeType']);
  //   const shareImageAspectRatioFixed = map.getIn(['formObj', 'shareImageAspectRatioFixed']);
  //
  //   const shareImageDefaultWidth = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageDefaultWidth']);
  //   const shareImageDefaultHeight = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageDefaultHeight']);
  //
  //
  //   const heightNumber = height ? parseInt(height, 10) : '';
  //   map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageHeight'], heightNumber);
  //
  //   if (shareImageAspectRatioFixed) {
  //
  //     if (heightNumber === '') {
  //       map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageWidth'], '');
  //     } else {
  //       const width = Math.round(heightNumber * (shareImageDefaultWidth / shareImageDefaultHeight));
  //       map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageWidth'], width);
  //     }
  //
  //   }
  //
  //   return map;
  //
  // }
  //
  //
  //
  //
  //
  // setFreeImageWidth(width) {
  //
  //   let map = this;
  //
  //   const currentThemeNameId = map.getIn(['formObj', 'currentThemeNameId']);
  //   const currentThemeType = map.getIn(['formObj', 'currentThemeType']);
  //   const freeImageAspectRatioFixed = map.getIn(['formObj', 'freeImageAspectRatioFixed']);
  //
  //   const freeImageDefaultWidth = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageDefaultWidth']);
  //   const freeImageDefaultHeight = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageDefaultHeight']);
  //
  //
  //   const widthNumber = width ? parseInt(width, 10) : '';
  //   map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageWidth'], widthNumber);
  //
  //   if (freeImageAspectRatioFixed) {
  //
  //     if (widthNumber === '') {
  //       map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageHeight'], '');
  //     } else {
  //       const height = Math.round(widthNumber * (freeImageDefaultHeight / freeImageDefaultWidth));
  //       map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageHeight'], height);
  //     }
  //
  //   }
  //
  //   // console.log('freeImageAspectRatioFixed = ', freeImageAspectRatioFixed);
  //   // console.log('freeImageDefaultWidth = ', freeImageDefaultWidth);
  //   // console.log('freeImageDefaultHeight = ', freeImageDefaultHeight);
  //   // console.log('widthNumber = ', widthNumber);
  //
  //   return map;
  //
  // }
  //
  // setFreeImagHeight(height) {
  //
  //   let map = this;
  //
  //   const currentThemeNameId = map.getIn(['formObj', 'currentThemeNameId']);
  //   const currentThemeType = map.getIn(['formObj', 'currentThemeType']);
  //   const freeImageAspectRatioFixed = map.getIn(['formObj', 'freeImageAspectRatioFixed']);
  //
  //   const freeImageDefaultWidth = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageDefaultWidth']);
  //   const freeImageDefaultHeight = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageDefaultHeight']);
  //
  //
  //   const heightNumber = height ? parseInt(height, 10) : '';
  //   map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageHeight'], heightNumber);
  //
  //   if (freeImageAspectRatioFixed) {
  //
  //     if (heightNumber === '') {
  //       map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageWidth'], '');
  //     } else {
  //       const width = Math.round(heightNumber * (freeImageDefaultWidth / freeImageDefaultHeight));
  //       map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageWidth'], width);
  //     }
  //
  //   }
  //
  //   return map;
  //
  // }
  //
  //
  // setFreeUploadImageWidth(width) {
  //
  //   let map = this;
  //
  //   const currentThemeNameId = map.getIn(['formObj', 'currentThemeNameId']);
  //   const currentThemeType = map.getIn(['formObj', 'currentThemeType']);
  //   const freeUploadImageAspectRatioFixed = map.getIn(['formObj', 'freeUploadImageAspectRatioFixed']);
  //
  //   const freeUploadImageDefaultWidth = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageDefaultWidth']);
  //   const freeUploadImageDefaultHeight = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageDefaultHeight']);
  //
  //
  //   const widthNumber = width ? parseInt(width, 10) : '';
  //   map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageWidth'], widthNumber);
  //
  //   if (freeUploadImageAspectRatioFixed) {
  //
  //     if (widthNumber === '') {
  //       map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageHeight'], '');
  //     } else {
  //       const height = Math.round(widthNumber * (freeUploadImageDefaultHeight / freeUploadImageDefaultWidth));
  //       map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageHeight'], height);
  //     }
  //
  //   }
  //
  //   return map;
  //
  // }
  //
  // setFreeUploadImageHeight(height) {
  //
  //   let map = this;
  //
  //   const currentThemeNameId = map.getIn(['formObj', 'currentThemeNameId']);
  //   const currentThemeType = map.getIn(['formObj', 'currentThemeType']);
  //   const freeUploadImageAspectRatioFixed = map.getIn(['formObj', 'freeUploadImageAspectRatioFixed']);
  //
  //   const freeUploadImageDefaultWidth = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageDefaultWidth']);
  //   const freeUploadImageDefaultHeight = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageDefaultHeight']);
  //
  //
  //   const heightNumber = height ? parseInt(height, 10) : '';
  //   map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageHeight'], heightNumber);
  //
  //   if (freeUploadImageAspectRatioFixed) {
  //
  //     if (heightNumber === '') {
  //       map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageWidth'], '');
  //     } else {
  //       const width = Math.round(heightNumber * (freeUploadImageDefaultWidth / freeUploadImageDefaultHeight));
  //       map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageWidth'], width);
  //     }
  //
  //   }
  //
  //   return map;
  //
  // }
  //
  // setFreeUploadImageFile(file, src, width, height, extension) {
  //
  //   // console.log('setShareImage');
  //
  //   // console.log('file = ', file);
  //   // console.log('src = ', src);
  //   // console.log('width = ', width);
  //   // console.log('height = ', height);
  //   // console.log('extension = ', extension);
  //
  //   let map = this;
  //
  //   const currentThemeNameId = map.getIn(['formObj', 'currentThemeNameId']);
  //   const currentThemeType = map.getIn(['formObj', 'currentThemeType']);
  //
  //   const freeUploadImageWidth = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageWidth']);
  //   const freeUploadImageHeight = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageHeight']);
  //
  //   map = map.setIn(['uploadImageObj', currentThemeNameId, currentThemeType, 'freeUploadImage', 'file'], file);
  //   map = map.setIn(['uploadImageObj', currentThemeNameId, currentThemeType, 'freeUploadImage', 'src'], src);
  //
  //   map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageDefaultWidth'], width);
  //   map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageDefaultHeight'], height);
  //
  //   if (freeUploadImageWidth === '' && freeUploadImageHeight === '') {
  //     map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageWidth'], width);
  //     map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageHeight'], height);
  //   }
  //
  //   map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageExtension'], extension);
  //
  //   return map;
  //
  // }
  //
  //
  // setSortShareObj() {
  //
  //   let map = this;
  //
  //   const currentThemeNameId = map.getIn(['formObj', 'currentThemeNameId']);
  //   const currentThemeType = map.getIn(['formObj', 'currentThemeType']);
  //
  //   const selectors = document.querySelectorAll('#sample-theme .box');
  //
  //   // console.log('selectors = ', selectors);
  //
  //   let tempMap = Map();
  //   // const tempObj = {};
  //
  //   Object.keys(selectors).forEach((key) => {
  //
  //     const id = selectors[key].id.split('gameusers-share-buttons-')[1];
  //     // console.log('id = ', id);
  //
  //     // const shareObj = map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'share', id]).toJS();
  //     // console.log('shareObj = ', shareObj);
  //     //
  //     // tempObj[id] = shareObj;
  //
  //     tempMap = tempMap.set(id, map.getIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'share', id]));
  //
  //   });
  //
  //   // console.log('tempMap = ', tempMap.toJS());
  //   // console.log('tempObj = ', tempObj);
  //
  //   map = map.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'share'], tempMap);
  //   // console.log('map = ', map.toJS());
  //
  //   return map;
  //
  // }
  //
  //
  // // setTestA() {
  // //   let map = this;
  // //   map = map.set('testA', 1);
  // //   return map;
  // // }
  // //
  // // setTestB() {
  // //   let map = this;
  // //   map = map.set('testB', 1);
  // //   return map;
  // // }
  //
  //
  // setDataObj(contentType, loadedDataObj) {
  //
  //   let map = this;
  //   let googleFontsList = map.getIn(['googleFontsArr']);
  //
  //
  //   if (!loadedDataObj) {
  //     return map;
  //   }
  //
  //
  //   let dataType = 'dataEditThemesObj';
  //
  //   if (contentType === 'designThemes') {
  //     dataType = 'dataDesignThemesObj';
  //   } else if (contentType === 'iconThemes') {
  //     dataType = 'dataIconThemesObj';
  //   }
  //
  //
  //
  //   Object.keys(loadedDataObj).forEach((key) => {
  //
  //     const value = loadedDataObj[key];
  //
  //     if (!value) {
  //       return;
  //     }
  //
  //     // console.log('key = ', key);
  //     // console.log('value = ', value);
  //     const themeType = `type${value.theme.type}`;
  //
  //
  //
  //     // console.log('dataType = ', dataType);
  //     // console.log('key = ', key);
  //     // console.log('themeType = ', themeType);
  //     // console.log('value = ', value);
  //     // console.log('map.get(dataType) = ', map.get(dataType).toJS());
  //
  //
  //     map = map.setIn([dataType, key, themeType], fromJS(value));
  //
  //
  //     let shareMap = OrderedMap();
  //
  //     Object.keys(value.share).forEach((key2) => {
  //       const value2 = value.share[key2];
  //       shareMap = shareMap.set(key2, Map(value2));
  //     });
  //
  //     map = map.setIn([dataType, key, themeType, 'share'], shareMap);
  //     // console.log('shareMap = ', shareMap.toJS());
  //
  //
  //
  //     if (!map.hasIn([dataType, key, 'type1'])) {
  //       map = map.setIn([dataType, key, 'type1'], fromJS(initialDataObjType1));
  //     }
  //
  //     if (!map.hasIn([dataType, key, 'type2'])) {
  //       map = map.setIn([dataType, key, 'type2'], fromJS(initialDataObjType2));
  //     }
  //
  //     map = map.setIn([dataType, key, 'namePrev'], value.name);
  //     map = map.setIn([dataType, key, 'idPrev'], value.id);
  //     map = map.setIn([dataType, key, 'openedThemeType'], themeType);
  //
  //     // console.log('themeType = ', themeType);
  //
  //
  //     // --------------------------------------------------
  //     //   Data Sample Themes Object
  //     // --------------------------------------------------
  //
  //     if (contentType === 'editThemes' && !map.hasIn(['dataSampleThemesObj', key])) {
  //       const dataObj = map.getIn([dataType, key]);
  //       map = map.setIn(['dataSampleThemesObj', key], dataObj);
  //     }
  //
  //     // console.log('setDataObj / dataObj = ', dataObj.toJS());
  //
  //
  //     // --------------------------------------------------
  //     //   Google Fonts
  //     // --------------------------------------------------
  //
  //     const countGoogleFont = map.getIn([dataType, key, themeType, 'countGoogleFont']);
  //
  //     if (!googleFontsList.includes(countGoogleFont)) {
  //       googleFontsList = googleFontsList.push(countGoogleFont);
  //     }
  //
  //   });
  //
  //   map = map.set('googleFontsArr', googleFontsList);
  //
  //
  //   // console.log('setDataObj / loadedDataObj = ', loadedDataObj);
  //   // console.log('setDataObj / map = ', map.toJS());
  //
  //   return map;
  //
  // }
  //
  // setPage(contentType, page) {
  //
  //   let map = this;
  //
  //   if (contentType === 'editThemes') {
  //     map = map.set('editThemesPage', page);
  //   } else if (contentType === 'designThemes') {
  //     map = map.set('designThemesPage', page);
  //   } else {
  //     map = map.set('iconThemesPage', page);
  //   }
  //
  //   // console.log('page = ', page);
  //   // console.log('map = ', map.toJS());
  //
  //   return map;
  //
  // }
  //
  //
  // setAjaxSaveTheme(name, id, namePrev, idPrev) {
  //
  //   let map = this;
  //
  //   const currentThemeNameId = `${name}-${id}`;
  //   const prevThemeNameId = `${namePrev}-${idPrev}`;
  //
  //   map = map.setIn(['formObj', 'currentThemeNameId'], currentThemeNameId);
  //
  //   if (namePrev && idPrev && (currentThemeNameId !== prevThemeNameId)) {
  //
  //     map = map.deleteIn(['dataSampleThemesObj', prevThemeNameId]);
  //     map = map.deleteIn(['dataEditThemesObj', prevThemeNameId]);
  //
  //     const uploadImageObj = map.getIn(['uploadImageObj', prevThemeNameId]);
  //     map = map.deleteIn(['uploadImageObj', prevThemeNameId]);
  //     map = map.setIn(['uploadImageObj', currentThemeNameId], uploadImageObj);
  //
  //   }
  //
  //   // console.log('currentThemeNameId = ', currentThemeNameId);
  //   // console.log('prevThemeNameId = ', prevThemeNameId);
  //   // console.log('setAjaxSaveTheme / map = ', map.toJS());
  //
  //   return map;
  //
  // }
  //
  //
  //
  // setCheckDownloadThemesList(themeNameId) {
  //
  //   let map = this;
  //
  //   let checkDownloadThemesList = map.getIn(['formObj', 'checkDownloadThemesList']);
  //
  //
  //   if (checkDownloadThemesList.includes(themeNameId)) {
  //     const number = checkDownloadThemesList.indexOf(themeNameId);
  //     checkDownloadThemesList = checkDownloadThemesList.delete(number);
  //   } else {
  //     checkDownloadThemesList = checkDownloadThemesList.push(themeNameId);
  //   }
  //
  //   // console.log('checkDownloadThemesList = ', checkDownloadThemesList.toJS());
  //
  //   map = map.setIn(['formObj', 'checkDownloadThemesList'], checkDownloadThemesList);
  //
  //   // console.log('map = ', map.toJS());
  //
  //   return map;
  //
  // }




}

// export default Model;
