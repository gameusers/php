// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { Route } from 'react-router-dom';
import 'whatwg-fetch';
import iziToast from 'izitoast';

import fetchApi from '../../../../js/modules/api';

// import ContentsAppShareButtons from '../components/share-buttons';
import ContentsAppShareButtonsRecruitment from '../components/share-buttons-recruitment';
import ContentsAppShareButtonsCampaign from '../components/share-buttons-campaign';
import ContentsAppPay from '../components/pay';
import ContentsAppPayVendor from '../components/pay-vendor';

import * as actions from '../actions/action';



/**
 * 表示するコンテンツを Route で指定
 * React v16 からの配列を返す方法を用いている
 * コンテンツを追加した場合は最後にコンマをつけることを忘れないように
 * @param {object} props props
 */
const ContentsApp = props => [
  // <Route key="/app/share-buttons" exact path="/app/share-buttons" render={() => <ContentsAppShareButtons {...props} />} />,
  <Route key="/app/share-buttons/recruitment" exact path="/app/share-buttons/recruitment" render={() => <ContentsAppShareButtonsRecruitment {...props} />} />,
  <Route key="/app/share-buttons/campaign" exact path="/app/share-buttons/campaign" render={() => <ContentsAppShareButtonsCampaign {...props} />} />,
  <Route key="/app/pay" exact path="/app/pay" render={() => <ContentsAppPay {...props} />} />,
  <Route key="/app/pay/vendor" exact path="/app/pay/vendor" render={() => <ContentsAppPayVendor {...props} />} />,
];



// --------------------------------------------------
//   mapStateToProps
// --------------------------------------------------

const mapStateToProps = (state) => {

  // const reducerRootMap = state.reducerRoot;
  const reducerAppMap = state.reducerApp;

  // console.log('state = ', state);
  // console.log('reducerRootMap = ', reducerRootMap.toJS());
  // console.log('reducerAppMap = ', reducerAppMap.toJS());


  return ({


    // --------------------------------------------------
    //   共通
    // --------------------------------------------------

    stateAppModel: reducerAppMap,


    // --------------------------------------------------
    //   シェアボタン / テーマ募集
    // --------------------------------------------------

    contentsAppShareButtonsRecruitmentFormAuthorName: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorNameMap', 'value']),
    contentsAppShareButtonsRecruitmentFormAuthorNameValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorNameMap', 'validationState']),
    contentsAppShareButtonsRecruitmentFormAuthorNameError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorNameMap', 'error']),

    contentsAppShareButtonsRecruitmentFormAuthorUrl: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorUrlMap', 'value']),
    contentsAppShareButtonsRecruitmentFormAuthorUrlValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorUrlMap', 'validationState']),
    contentsAppShareButtonsRecruitmentFormAuthorUrlError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorUrlMap', 'error']),

    contentsAppShareButtonsRecruitmentFormFile: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'fileMap', 'value']),
    contentsAppShareButtonsRecruitmentFormFileValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'fileMap', 'validationState']),
    contentsAppShareButtonsRecruitmentFormFileError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'fileMap', 'error']),

    contentsAppShareButtonsRecruitmentFormMail: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'mailMap', 'value']),
    contentsAppShareButtonsRecruitmentFormMailValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'mailMap', 'validationState']),
    contentsAppShareButtonsRecruitmentFormMailError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'mailMap', 'error']),

    contentsAppShareButtonsRecruitmentFormWebSiteName: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteNameMap', 'value']),
    contentsAppShareButtonsRecruitmentFormWebSiteNameValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteNameMap', 'validationState']),
    contentsAppShareButtonsRecruitmentFormWebSiteNameError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteNameMap', 'error']),

    contentsAppShareButtonsRecruitmentFormWebSiteUrl: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteUrlMap', 'value']),
    contentsAppShareButtonsRecruitmentFormWebSiteUrlValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteUrlMap', 'validationState']),
    contentsAppShareButtonsRecruitmentFormWebSiteUrlError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteUrlMap', 'error']),

    contentsAppShareButtonsRecruitmentFormComment: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'commentMap', 'value']),
    contentsAppShareButtonsRecruitmentFormCommentValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'commentMap', 'validationState']),
    contentsAppShareButtonsRecruitmentFormCommentError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'commentMap', 'error']),

    contentsAppShareButtonsRecruitmentFormAgreement: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'agreementMap', 'value']),
    contentsAppShareButtonsRecruitmentFormAgreementValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'agreementMap', 'validationState']),
    contentsAppShareButtonsRecruitmentFormAgreementError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'agreementMap', 'error']),



    // --------------------------------------------------
    //   シェアボタン / キャンペーン
    // --------------------------------------------------

    contentsAppShareButtonsCampaignFormBlogName: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogNameMap', 'value']),
    contentsAppShareButtonsCampaignFormBlogNameValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogNameMap', 'validationState']),
    contentsAppShareButtonsCampaignFormBlogNameError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogNameMap', 'error']),

    contentsAppShareButtonsCampaignFormBlogUrl: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogUrlMap', 'value']),
    contentsAppShareButtonsCampaignFormBlogUrlValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogUrlMap', 'validationState']),
    contentsAppShareButtonsCampaignFormBlogUrlError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogUrlMap', 'error']),

    contentsAppShareButtonsCampaignFormArticleUrl: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'articleUrlMap', 'value']),
    contentsAppShareButtonsCampaignFormArticleUrlValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'articleUrlMap', 'validationState']),
    contentsAppShareButtonsCampaignFormArticleUrlError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'articleUrlMap', 'error']),

    contentsAppShareButtonsCampaignFormMail: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'mailMap', 'value']),
    contentsAppShareButtonsCampaignFormMailValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'mailMap', 'validationState']),
    contentsAppShareButtonsCampaignFormMailError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'mailMap', 'error']),

    contentsAppShareButtonsCampaignFormComment: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'commentMap', 'value']),
    contentsAppShareButtonsCampaignFormCommentValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'commentMap', 'validationState']),
    contentsAppShareButtonsCampaignFormCommentError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'commentMap', 'error']),

    contentsAppShareButtonsCampaignFormAgreement: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'agreementMap', 'value']),
    contentsAppShareButtonsCampaignFormAgreementValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'agreementMap', 'validationState']),
    contentsAppShareButtonsCampaignFormAgreementError: reducerAppMap.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'agreementMap', 'error']),




    // --------------------------------------------------
    //   購入
    // --------------------------------------------------

    contentsAppPayFormShareButtonsWebSiteName: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteNameMap', 'value']),
    contentsAppPayFormShareButtonsWebSiteNameValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteNameMap', 'validationState']),
    contentsAppPayFormShareButtonsWebSiteNameError: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteNameMap', 'error']),

    contentsAppPayFormShareButtonsWebSiteUrl: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteUrlMap', 'value']),
    contentsAppPayFormShareButtonsWebSiteUrlValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteUrlMap', 'validationState']),
    contentsAppPayFormShareButtonsWebSiteUrlError: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteUrlMap', 'error']),

    contentsAppPayFormShareButtonsAgreement: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'agreementMap', 'value']),
    contentsAppPayFormShareButtonsAgreementValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'agreementMap', 'validationState']),
    contentsAppPayFormShareButtonsAgreementError: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'agreementMap', 'error']),

    contentsAppPayFormShareButtonsPurchased: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'purchased']),


  });

};



// --------------------------------------------------
//   mapDispatchToProps
// --------------------------------------------------

const mapDispatchToProps = (dispatch) => {

  const bindActionObj = bindActionCreators(actions, dispatch);



  // --------------------------------------------------
  //   シェアボタン
  // --------------------------------------------------

  /**
   * テーマ募集 / 応募フォーム
   * @param  {Model}   stateModel    Modelクラスのインスタンス
   * @param  {Model}   stateAppModel Modelクラスのインスタンス
   * @param  {element} currentTarget ローディングを表示するボタンのエレメント
   */
  bindActionObj.funcSendMailRecruitmentTheme = async (stateModel, stateAppModel, currentTarget) => {


    // --------------------------------------------------
    //   Loading Start
    // --------------------------------------------------

    let instanceLadda = null;

    if (currentTarget) {
      instanceLadda = Ladda.create(currentTarget);
      instanceLadda.start();
    }


    // --------------------------------------------------
    //   Get Data
    // --------------------------------------------------

    const urlBase = stateModel.get('urlBase');

    const authorName = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorNameMap', 'value']);
    const authorNameError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorNameMap', 'error']);

    const authorUrl = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorUrlMap', 'value']);
    const authorUrlError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'authorUrlMap', 'error']);

    const file = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'fileMap', 'value']);
    const fileError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'fileMap', 'error']);

    const mail = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'mailMap', 'value']);
    const mailError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'mailMap', 'error']);

    const webSiteName = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteNameMap', 'value']);
    const webSiteNameError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteNameMap', 'error']);

    const webSiteUrl = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteUrlMap', 'value']);
    const webSiteUrlError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'webSiteUrlMap', 'error']);

    const comment = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'commentMap', 'value']);
    const commentError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'commentMap', 'error']);

    // const agreement = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'agreementMap', 'value']);
    const agreementError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'recruitmentMap', 'formMap', 'agreementMap', 'error']);



    // console.log('funcSendMailRecruitmentTheme');
    // console.log('urlBase = ', urlBase);
    // console.log('authorName = ', authorName);
    // console.log('authorUrl = ', authorUrl);
    // console.log('file = ', file);
    // console.log('mail = ', mail);
    // console.log('webSiteName = ', webSiteName);
    // console.log('webSiteUrl = ', webSiteUrl);
    // console.log('comment = ', comment);
    // console.log('agreement = ', agreement);
    //
    // console.log('authorNameError = ', authorNameError);
    // console.log('authorUrlError = ', authorUrlError);
    // console.log('fileError = ', fileError);
    // console.log('mailError = ', mailError);
    // console.log('webSiteNameError = ', webSiteNameError);
    // console.log('webSiteUrlError = ', webSiteUrlError);
    // console.log('commentError = ', commentError);
    // console.log('agreementError = ', agreementError);
    // return;


    // --------------------------------------------------
    //   フォームにエラーがある場合は処理停止
    // --------------------------------------------------

    if (authorNameError || authorUrlError || fileError || mailError || webSiteNameError || webSiteUrlError || commentError || agreementError) {

      iziToast.error({
        title: 'Error',
        message: 'フォームの内容に問題があります。'
      });


      // --------------------------------------------------
      //   Loading Stop
      // --------------------------------------------------

      if (currentTarget && instanceLadda.isLoading) {
        instanceLadda.stop();
      }


      return;

    }



    // --------------------------------------------------
    //   FormData
    // --------------------------------------------------

    const formData = new FormData();

    formData.append('apiType', 'sendMailRecruitmentTheme');

    formData.append('authorName', authorName);
    formData.append('authorUrl', authorUrl);
    if (file) formData.append('file', file);
    formData.append('mail', mail);
    formData.append('webSiteName', webSiteName);
    formData.append('webSiteUrl', webSiteUrl);
    formData.append('comment', comment);


    // --------------------------------------------------
    //   Await & Dispatch
    // --------------------------------------------------

    try {

      const returnObj = await fetchApi(`${urlBase}api/react.json`, 'POST', 'same-origin', 'same-origin', formData);
      // console.log('returnObj = ', returnObj);

      if (returnObj.error) {
        throw new Error();
      }


      iziToast.success({
        title: 'OK',
        message: '応募が完了しました。テーマを確認させていただきますので、今しばらくお待ち下さい。'
      });

    } catch (e) {
      // console.log('e = ', e);

      iziToast.error({
        title: 'Error',
        message: '送信ができませんでした。'
      });

    }


    // --------------------------------------------------
    //   Loading Stop
    // --------------------------------------------------

    if (currentTarget && instanceLadda.isLoading) {
      instanceLadda.stop();
    }

  };



  /**
   * キャンペーン / 応募フォーム
   * @param  {Model}   stateModel    Modelクラスのインスタンス
   * @param  {Model}   stateAppModel Modelクラスのインスタンス
   * @param  {element} currentTarget ローディングを表示するボタンのエレメント
   */
  bindActionObj.funcSendMailCampaign = async (stateModel, stateAppModel, currentTarget) => {


    // --------------------------------------------------
    //   Loading Start
    // --------------------------------------------------

    let instanceLadda = null;

    if (currentTarget) {
      instanceLadda = Ladda.create(currentTarget);
      instanceLadda.start();
    }


    // --------------------------------------------------
    //   Get Data
    // --------------------------------------------------

    const urlBase = stateModel.get('urlBase');

    const blogName = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogNameMap', 'value']);
    const blogNameError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogNameMap', 'error']);

    const blogUrl = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogUrlMap', 'value']);
    const blogUrlError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'blogUrlMap', 'error']);

    const articleUrl = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'articleUrlMap', 'value']);
    const articleUrlError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'articleUrlMap', 'error']);

    const mail = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'mailMap', 'value']);
    const mailError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'mailMap', 'error']);

    const comment = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'commentMap', 'value']);
    const commentError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'commentMap', 'error']);

    const agreementError = stateAppModel.getIn(['contentsMap', 'appMap', 'shareButtonsMap', 'campaignMap', 'formMap', 'agreementMap', 'error']);



    // console.log('funcSendMailCampaign');

    // console.log('urlBase = ', urlBase);

    // console.log('blogName = ', blogName);
    // console.log('blogUrl = ', blogUrl);
    // console.log('articleUrl = ', articleUrl);
    // console.log('mail = ', mail);
    // console.log('comment = ', comment);
    //
    // console.log('blogNameError = ', blogNameError);
    // console.log('blogUrlError = ', blogUrlError);
    // console.log('articleUrlError = ', articleUrlError);
    // console.log('mailError = ', mailError);
    // console.log('commentError = ', commentError);
    // console.log('agreementError = ', agreementError);
    // return;


    // --------------------------------------------------
    //   フォームにエラーがある場合は処理停止
    // --------------------------------------------------

    if (blogNameError || blogUrlError || articleUrlError || mailError || commentError || agreementError) {

      iziToast.error({
        title: 'Error',
        message: 'フォームの内容に問題があります。'
      });


      // --------------------------------------------------
      //   Loading Stop
      // --------------------------------------------------

      if (currentTarget && instanceLadda.isLoading) {
        instanceLadda.stop();
      }


      return;

    }



    // --------------------------------------------------
    //   FormData
    // --------------------------------------------------

    const formData = new FormData();

    formData.append('apiType', 'sendMailCampaign');

    formData.append('blogName', blogName);
    formData.append('blogUrl', blogUrl);
    formData.append('articleUrl', articleUrl);
    formData.append('mail', mail);
    formData.append('comment', comment);


    // --------------------------------------------------
    //   Await & Dispatch
    // --------------------------------------------------

    try {

      const returnObj = await fetchApi(`${urlBase}api/react.json`, 'POST', 'same-origin', 'same-origin', formData);
      // console.log('returnObj = ', returnObj);

      if (returnObj.error) {
        throw new Error();
      }


      iziToast.success({
        title: 'OK',
        message: '応募が完了しました。記事を確認させていただきますので、今しばらくお待ち下さい。'
      });

    } catch (e) {
      // console.log('e = ', e);

      iziToast.error({
        title: 'Error',
        message: '送信ができませんでした。'
      });

    }


    // --------------------------------------------------
    //   Loading Stop
    // --------------------------------------------------

    if (currentTarget && instanceLadda.isLoading) {
      instanceLadda.stop();
    }

  };





  // --------------------------------------------------
  //   購入
  // --------------------------------------------------

  /**
   * 有料プランに申し込む
   * @param  {Model}   stateModel    Modelクラスのインスタンス
   * @param  {Model}   stateAppModel Modelクラスのインスタンス
   * @param  {string}  plan          プラン - premium / business
   * @param  {object}  stripeObj     Stripeのオブジェクト
   */
  bindActionObj.funcInsertShareButtonsPaidPlan = async (stateModel, stateAppModel, plan, stripeObj) => {


    // --------------------------------------------------
    //   Get Data
    // --------------------------------------------------

    const urlBase = stateModel.get('urlBase');

    const webSiteName = stateAppModel.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteNameMap', 'value']);
    const webSiteUrl = stateAppModel.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteUrlMap', 'value']);


    // console.log('funcInsertShareButtonsPay');
    // console.log('urlBase = ', urlBase);
    // console.log('plan = ', plan);
    // console.log('webSiteName = ', webSiteName);
    // console.log('webSiteUrl = ', webSiteUrl);
    // console.log('stripeObj = ', stripeObj);
    // console.log('stripeToken = ', stripeObj.id);
    // console.log('stripeTokenType = ', stripeObj.type);
    // console.log('stripeEmail = ', stripeObj.email);
    // console.log('webSiteNameError = ', webSiteNameError);
    // console.log('webSiteUrlError = ', webSiteUrlError);
    // console.log('agreementError = ', agreementError);
    // return;



    // --------------------------------------------------
    //   FormData
    // --------------------------------------------------

    const formData = new FormData();

    formData.append('apiType', 'insertShareButtonsPaidPlan');

    formData.append('plan', plan);
    formData.append('webSiteName', webSiteName);
    formData.append('webSiteUrl', webSiteUrl);
    formData.append('stripeToken', stripeObj.id);
    formData.append('stripeTokenType', stripeObj.type);
    formData.append('stripeEmail', stripeObj.email);


    // --------------------------------------------------
    //   Await & Dispatch
    // --------------------------------------------------

    try {

      const returnObj = await fetchApi(`${urlBase}api/react.json`, 'POST', 'same-origin', 'same-origin', formData);
      // console.log('returnObj = ', returnObj);

      if (returnObj.error) {
        throw new Error();
      }


      dispatch(actions.funcContentsAppPayFormShareButtonsPurchased(true));


      iziToast.success({
        title: 'OK',
        message: '有料プランの申し込みが完了しました。'
      });

    } catch (e) {
      // console.log('e = ', e);

      iziToast.error({
        title: 'Error',
        message: '有料プランの申し込みに失敗しました。'
      });

    }

  };




  return bindActionObj;

};



const ContainerContentsApp = connect(
  mapStateToProps,
  mapDispatchToProps
)(ContentsApp);



export default ContainerContentsApp;
