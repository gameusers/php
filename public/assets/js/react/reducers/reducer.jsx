// --------------------------------------------------
//   Import
// --------------------------------------------------

import { List } from 'immutable';
import { Model } from '../models/model';



const reducer = (state = new Model(), action) => {

  const currentThemeNameId = state.getIn(['formObj', 'currentThemeNameId']);
  const currentThemeType = state.getIn(['formObj', 'currentThemeType']);
  const shareType = state.getIn(['formObj', 'shareType']);

  switch (action.type) {

    case 'INITIAL_ASYNCHRONOUS': {
      return state
        .set('designThemesMap', action.designThemesMap)
        .set('iconThemesMap', action.iconThemesMap)
        .setDataObj('editThemes', action.loadedDataEditThemesObj)
        .setDataObj('designThemes', action.loadedDataDesignThemesObj)
        .setDataObj('iconThemes', action.loadedDataIconThemesObj)
        .set('randomDesignThemesList', List(action.randomDesignThemesArr))
        .set('randomIconThemesList', List(action.randomIconThemesArr));
    }



    case 'TOP_THEME': {
      return state.set('topTheme', action.value);
    }

    case 'BOTTOM_THEME': {
      return state.set('bottomTheme', action.value);
    }



    case 'PLAN': {
      return state.set('plan', action.value);
    }



    case 'TOGGLE_EDIT_FORM': {
      return state.setToggleEditForm(action.value);
    }



    case 'CHECK_STICKY_SAMPLE_THEME': {
      return state.setIn(['formObj', 'checkStickySampleTheme'], action.value);
    }

    case 'CURRENT_THEME_TYPE': {
      return state
        .setIn(['formObj', 'currentThemeType'], action.value)
        .setIn(['dataSampleThemesObj', currentThemeNameId, 'openedThemeType'], action.value);
    }




    case 'SHARE_IMAGE_ASPECT_RATIO_FIXED': {
      return state.setIn(['formObj', 'shareImageAspectRatioFixed'], action.value);
    }

    case 'FREE_IMAGE_ASPECT_RATIO_FIXED': {
      return state.setIn(['formObj', 'freeImageAspectRatioFixed'], action.value);
    }

    case 'FREE_UPLOAD_IMAGE_ASPECT_RATIO_FIXED': {
      return state.setIn(['formObj', 'freeUploadImageAspectRatioFixed'], action.value);
    }

    case 'NAME': {
      return state
        .setIn(['dataSampleThemesObj', currentThemeNameId, 'type1', 'name'], action.value)
        .setIn(['dataSampleThemesObj', currentThemeNameId, 'type2', 'name'], action.value);
    }

    case 'SHARE_IMAGE': {
      return state.setShareImage(action.file, action.src, action.width, action.height, action.extension);
    }

    case 'OPTION_TYPE': {
      return state.setIn(['formObj', 'optionType'], action.value);
    }

    case 'SHARE_TYPE': {
      return state.setIn(['formObj', 'shareType'], action.value);
    }

    case 'SHARE_BUTTON': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'share', shareType, 'button'], action.value);
    }

    case 'SHARE_COUNT': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'share', shareType, 'count'], action.value);
    }

    case 'SHARE_COUNT_DEFAULT_TEXT': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'share', shareType, 'countDefaultText'], action.value);
    }

    case 'SHARE_COUNT_MIN': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'share', shareType, 'countMin'], number);
    }

    case 'SHARE_COUNT_MAX': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'share', shareType, 'countMax'], number);
    }

    case 'SHARE_IMAGE_DELETE': {
      return state
        .deleteIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'share', shareType])
        .deleteIn(['uploadImageObj', currentThemeNameId, currentThemeType, shareType]);
    }


    case 'SHARE_IMAGE_VERTICAL_ALIGN': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageVerticalAlign'], action.value);
    }

    case 'SHARE_IMAGE_WIDTH': {
      return state.setShareImageWidth(action.value);
    }

    case 'SHARE_IMAGE_HEIGHT': {
      return state.setShareImageHeight(action.value);
    }

    case 'SHARE_IMAGE_MARGIN_TOP': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageMarginTop'], number);
    }

    case 'SHARE_IMAGE_MARGIN_RIGHT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageMarginRight'], number);
    }

    case 'SHARE_IMAGE_MARGIN_BOTTOM': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageMarginBottom'], number);
    }

    case 'SHARE_IMAGE_MARGIN_LEFT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'shareImageMarginLeft'], number);
    }



    case 'COUNT_INPUT': {
      return state.setIn(['formObj', 'countInput'], action.value);
    }

    case 'COUNT_INPUT_MIN': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['formObj', 'countInputMin'], number);
    }

    case 'COUNT_INPUT_MAX': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['formObj', 'countInputMax'], number);
    }

    case 'COUNT': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'count'], action.value);
    }

    case 'COUNT_DIRECTION': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countDirection'], action.value);
    }

    case 'COUNT_VERTICAL_ALIGN': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countVerticalAlign'], action.value);
    }

    case 'COUNT_WIDTH': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countWidth'], number);
    }

    case 'COUNT_HEIGHT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countHeight'], number);
    }

    case 'COUNT_MARGIN_TOP': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countMarginTop'], number);
    }

    case 'COUNT_MARGIN_RIGHT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countMarginRight'], number);
    }

    case 'COUNT_MARGIN_BOTTOM': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countMarginBottom'], number);
    }

    case 'COUNT_MARGIN_LEFT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countMarginLeft'], number);
    }

    case 'COUNT_PADDING_TOP': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countPaddingTop'], number);
    }

    case 'COUNT_PADDING_RIGHT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countPaddingRight'], number);
    }

    case 'COUNT_PADDING_BOTTOM': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countPaddingBottom'], number);
    }

    case 'COUNT_PADDING_LEFT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countPaddingLeft'], number);
    }

    case 'COUNT_BORDER_COLOR': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countBorderColor'], action.value);
    }

    case 'COUNT_BORDER_RADIUS': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countBorderRadius'], number);
    }

    case 'COUNT_BACKGROUND_COLOR': {
      return state.setIn(['formObj', 'countBackgroundColor'], action.value);
    }

    case 'COUNT_BACKGROUND_COLOR_HEX': {
      return state.setIn(['formObj', 'countBackgroundColorHex'], action.value);
    }

    case 'COUNT_TOP': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countTop'], number);
    }

    case 'COUNT_LEFT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countLeft'], number);
    }

    case 'COUNT_TEXT_ALIGN': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countTextAlign'], action.value);
    }

    case 'COUNT_FONT': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countFont'], action.value);
    }

    case 'COUNT_GOOGLE_FONT': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countGoogleFont'], action.value);
    }

    case 'COUNT_FONT_COLOR': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countFontColor'], action.value);
    }

    case 'COUNT_FONT_SIZE': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countFontSize'], number);
    }

    case 'COUNT_FONT_STYLE': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countFontStyle'], action.value);
    }

    case 'COUNT_FONT_WEIGHT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'countFontWeight'], number);
    }



    case 'FREE_IMAGE': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImage'], action.value);
    }

    case 'FREE_IMAGE_VERTICAL_ALIGN': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageVerticalAlign'], action.value);
    }

    case 'FREE_IMAGE_WIDTH': {
      return state.setFreeImageWidth(action.value);
    }

    case 'FREE_IMAGE_HEIGHT': {
      return state.setFreeImagHeight(action.value);
    }

    case 'FREE_IMAGE_MARGIN_TOP': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageMarginTop'], number);
    }

    case 'FREE_IMAGE_MARGIN_RIGHT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageMarginRight'], number);
    }

    case 'FREE_IMAGE_MARGIN_BOTTOM': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageMarginBottom'], number);
    }

    case 'FREE_IMAGE_MARGIN_LEFT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeImageMarginLeft'], number);
    }



    case 'FREE_UPLOAD_IMAGE': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImage'], action.value);
    }

    case 'FREE_UPLOAD_IMAGE_FILE': {
      return state.setFreeUploadImageFile(action.file, action.src, action.width, action.height, action.extension);
    }

    case 'FREE_UPLOAD_IMAGE_URL': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageUrl'], action.value);
    }

    case 'FREE_UPLOAD_IMAGE_ALT': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageAlt'], action.value);
    }

    case 'FREE_UPLOAD_IMAGE_VERTICAL_ALIGN': {
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageVerticalAlign'], action.value);
    }

    case 'FREE_UPLOAD_IMAGE_WIDTH': {
      return state.setFreeUploadImageWidth(action.value);
    }

    case 'FREE_UPLOAD_IMAGE_HEIGHT': {
      return state.setFreeUploadImageHeight(action.value);
    }

    case 'FREE_UPLOAD_IMAGE_MARGIN_TOP': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageMarginTop'], number);
    }

    case 'FREE_UPLOAD_IMAGE_MARGIN_RIGHT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageMarginRight'], number);
    }

    case 'FREE_UPLOAD_IMAGE_MARGIN_BOTTOM': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageMarginBottom'], number);
    }

    case 'FREE_UPLOAD_IMAGE_MARGIN_LEFT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'freeUploadImageMarginLeft'], number);
    }



    case 'BOX_MARGIN_TOP': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'boxMarginTop'], number);
    }

    case 'BOX_MARGIN_RIGHT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'boxMarginRight'], number);
    }

    case 'BOX_MARGIN_BOTTOM': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'boxMarginBottom'], number);
    }

    case 'BOX_MARGIN_LEFT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'boxMarginLeft'], number);
    }

    case 'MARGIN_TOP': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'marginTop'], number);
    }

    case 'MARGIN_RIGHT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'marginRight'], number);
    }

    case 'MARGIN_BOTTOM': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'marginBottom'], number);
    }

    case 'MARGIN_LEFT': {
      const number = action.value ? parseInt(action.value, 10) : '';
      return state.setIn(['dataSampleThemesObj', currentThemeNameId, currentThemeType, 'marginLeft'], number);
    }



    case 'SORT_SHARE_OBJ': {
      return state.setSortShareObj();
    }



    case 'CHANGE_SHARE_BUTTONS_LIST': {
      return state
        .setDataObj(action.contentType, action.loadedDataObj)
        .setPage(action.contentType, action.page);
    }



    case 'AJAX_SAVE_THEME': {
      return state
        .set('editThemesMap', action.editThemesMap)
        .setAjaxSaveTheme(action.name, action.id, action.namePrev, action.idPrev)
        .setDataObj('editThemes', action.loadedDataObj);
    }

    case 'AJAX_DELETE_THEME': {
      return state
        .set('editThemesMap', action.editThemesMap)
        .setDataObj('editThemes', action.loadedDataObj);
    }

    case 'AJAX_MOVE_EDIT_TAB': {
      return state
        .set('editThemesMap', action.editThemesMap)
        .setDataObj('editThemes', action.loadedDataObj);
    }

    case 'AJAX_SAVE_OPTION': {
      const numberPhp = parseInt(action.php, 10);
      return state
        .set('php', numberPhp)
        .set('twitterApiType', action.twitterApiType)
        .set('rssUrl', action.rssUrl);
    }



    case 'CHECK_DOWNLOAD_THEMES': {
      return state.setCheckDownloadThemesList(action.value);
    }



    default: {
      return state;
    }

  }

};



export default reducer;
