// --------------------------------------------------
//   Import
// --------------------------------------------------

import { ModelApp } from '../models/model';



const reducerApp = (state = new ModelApp(), action) => {


  switch (action.type) {


    // --------------------------------------------------
    //   シェアボタン / テーマ募集
    // --------------------------------------------------

    case 'CONTENTS_APP_SHARE_BUTTONS_RECRUITMENT_FORM_AUTHOR_NAME': {
      return state.setContentsAppShareButtonsRecruitmentFormAuthorName(action.value);
    }

    case 'CONTENTS_APP_SHARE_BUTTONS_RECRUITMENT_FORM_AUTHOR_URL': {
      return state.setContentsAppShareButtonsRecruitmentFormAuthorUrl(action.value);
    }

    case 'CONTENTS_APP_SHARE_BUTTONS_RECRUITMENT_FORM_FILE': {
      return state.setContentsAppShareButtonsRecruitmentFormFile(action.value);
    }

    case 'CONTENTS_APP_SHARE_BUTTONS_RECRUITMENT_FORM_MAIL': {
      return state.setContentsAppShareButtonsRecruitmentFormMail(action.value);
    }

    case 'CONTENTS_APP_SHARE_BUTTONS_RECRUITMENT_FORM_WEB_SITE_NAME': {
      return state.setContentsAppShareButtonsRecruitmentFormWebSiteName(action.value);
    }

    case 'CONTENTS_APP_SHARE_BUTTONS_RECRUITMENT_FORM_WEB_SITE_URL': {
      return state.setContentsAppShareButtonsRecruitmentFormWebSiteUrl(action.value);
    }

    case 'CONTENTS_APP_SHARE_BUTTONS_RECRUITMENT_FORM_COMMENT': {
      return state.setContentsAppShareButtonsRecruitmentFormComment(action.value);
    }

    case 'CONTENTS_APP_SHARE_BUTTONS_RECRUITMENT_FORM_AGREEMENT': {
      return state.setContentsAppShareButtonsRecruitmentFormAgreement(action.value);
    }



    // --------------------------------------------------
    //   シェアボタン / キャンペーン
    // --------------------------------------------------

    case 'CONTENTS_APP_SHARE_BUTTONS_CAMPAIGN_FORM_BLOG_NAME': {
      return state.setContentsAppShareButtonsCampaignFormBlogName(action.value);
    }

    case 'CONTENTS_APP_SHARE_BUTTONS_CAMPAIGN_FORM_BLOG_URL': {
      return state.setContentsAppShareButtonsCampaignFormBlogUrl(action.value);
    }

    case 'CONTENTS_APP_SHARE_BUTTONS_CAMPAIGN_FORM_ARTICLE_URL': {
      return state.setContentsAppShareButtonsCampaignFormArticleUrl(action.value);
    }

    case 'CONTENTS_APP_SHARE_BUTTONS_CAMPAIGN_FORM_MAIL': {
      return state.setContentsAppShareButtonsCampaignFormMail(action.value);
    }

    case 'CONTENTS_APP_SHARE_BUTTONS_CAMPAIGN_FORM_COMMENT': {
      return state.setContentsAppShareButtonsCampaignFormComment(action.value);
    }

    case 'CONTENTS_APP_SHARE_BUTTONS_CAMPAIGN_FORM_AGREEMENT': {
      return state.setContentsAppShareButtonsCampaignFormAgreement(action.value);
    }




    // --------------------------------------------------
    //   購入
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
