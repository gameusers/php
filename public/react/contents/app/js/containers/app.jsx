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
  <Route key="/app/pay" exact path="/app/pay" render={() => <ContentsAppPay {...props} />} />,
  <Route key="/app/pay/vendor" exact path="/app/pay/vendor" render={() => <ContentsAppPayVendor {...props} />} />
];

/**
 * 表示するコンテンツを Route で指定
 * Switchを使っているのはなにかで囲わないといけないため
 * divを利用するとなぜか横幅がおかしくなる
 * React v16になると囲い不要で配列を返すことができるのでそちらを利用すること
 * https://stackoverflow.com/questions/43225239/error-adjacent-jsx-elements-must-be-wrapped-in-an-enclosing-tag
 * @param {object} props props
 */
// const ContentsApp = props => (
//   <Switch>
//     <Route exact path="/app/share-buttons" render={() => <ContentsAppShareButtons {...props} />} />
//     <Route exact path="/app/pay" render={() => <ContentsAppPay {...props} />} />
//     <Route exact path="/app/pay/vendor" render={() => <ContentsAppPayVendor {...props} />} />
//   </Switch>
// );



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
    //   コンテンツ / アプリ / 購入
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
  //   アプリ
  // --------------------------------------------------

  /**
   * 有料プランに申し込む
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


      // const returnObj = await promiseReactJsonPost(urlBase, formData);

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
