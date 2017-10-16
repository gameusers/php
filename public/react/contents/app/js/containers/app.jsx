// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { Switch, Route } from 'react-router-dom';
import 'whatwg-fetch';

// import ContentsAppShareButtons from '../components/share-buttons';
import ContentsAppPay from '../components/pay';

import * as actions from '../actions/action';



/**
 * 表示するコンテンツを Route で指定
 * Switchを使っているのはなにかで囲わないといけないため
 * divを利用するとなぜか横幅がおかしくなる
 * React v16になると囲い不要で配列を返すことができるのでそちらを利用すること
 * https://stackoverflow.com/questions/43225239/error-adjacent-jsx-elements-must-be-wrapped-in-an-enclosing-tag
 * @param {object} props props
 */
const ContentsApp = props => (
  // <div style={{ width: '100%' }}>
  <Switch>
    {/* <Route exact path="/app/share-buttons" render={() => <ContentsAppShareButtons {...props} />} /> */}
    <Route exact path="/app/pay" render={() => <ContentsAppPay {...props} />} />
  </Switch>
  // </div>
);



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
    //   コンテンツ / アプリ / 購入
    // --------------------------------------------------

    contentsAppPayFormShareButtonsWebSiteName: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteNameMap', 'value']),
    contentsAppPayFormShareButtonsWebSiteNameValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteNameMap', 'validationState']),
    contentsAppPayFormShareButtonsWebSiteUrl: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteUrlMap', 'value']),
    contentsAppPayFormShareButtonsWebSiteUrlValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'webSiteUrlMap', 'validationState']),
    contentsAppPayFormShareButtonsAgreement: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'agreementMap', 'value']),
    contentsAppPayFormShareButtonsAgreementValidationState: reducerAppMap.getIn(['contentsMap', 'appMap', 'payMap', 'formShareButtonsMap', 'agreementMap', 'validationState']),


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
   * @param  {Model}  stateModel Modelクラスのインスタンス
   * @param  {object}  stripeObj  Stripeのオブジェクト
   */
  bindActionObj.funcInsertShareButtonsPaidPlan = async (stateModel, plan, stripeObj) => {

    console.log('funcInsertShareButtonsPay');
    console.log('stripeObj = ', stripeObj);
    console.log('stripeToken = ', stripeObj.id);
    console.log('stripeTokenType = ', stripeObj.type);
    console.log('stripeEmail = ', stripeObj.email);

    // --------------------------------------------------
    //   Get Data
    // --------------------------------------------------

    const urlBase = stateModel.get('urlBase');


    // --------------------------------------------------
    //   FormData
    // --------------------------------------------------

    const formData = new FormData();

    formData.append('apiType', 'insertShareButtonsPaidPlan');
    formData.append('plan', plan);
    formData.append('stripeToken', stripeObj.id);
    formData.append('stripeTokenType', stripeObj.type);
    formData.append('stripeEmail', stripeObj.email);


    // --------------------------------------------------
    //   Await & Dispatch
    // --------------------------------------------------

    try {


      const returnObj = await funcPromise(urlBase, formData);

      // console.log('returnObj = ', returnObj);

      if (returnObj.error) {
        throw new Error();
      }


      // console.log('gameCommunityRenewalList = ', gameCommunityRenewalList);
      // console.log('gameCommunityAccessList = ', gameCommunityAccessList);
      // console.log('userCommunityAccessList = ', userCommunityAccessList);


      // dispatch(actions.funcSelectFooterCardType(cardType, gameCommunityRenewalList, gameCommunityAccessList, userCommunityAccessList));


    } catch (e) {
      // continue regardless of error
    }

  };




  return bindActionObj;

};



const ContainerContentsApp = connect(
  mapStateToProps,
  mapDispatchToProps
)(ContentsApp);



export default ContainerContentsApp;
