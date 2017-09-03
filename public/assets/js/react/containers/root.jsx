// --------------------------------------------------
//   Import
// --------------------------------------------------

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import 'whatwg-fetch';
import 'babel-polyfill';
import Root from '../components/root';
import * as actions from '../actions/action';
// import { GAMEUSERS_API_URL, THEME_DESIGN_URL, THEME_ICON_URL, instanceGameUsersShareButtonsOption, fromJSOrdered } from '../models/model';


// --------------------------------------------------
//   mapStateToProps
// --------------------------------------------------

const mapStateToProps = state => ({


  // --------------------------------------------------
  //   共通
  // --------------------------------------------------

  agentType: state.get('agentType'),
  host: state.get('host'),
  userAgent: state.get('userAgent'),
  userNo: state.get('userNo'),
  playerId: state.get('playerId'),
  language: state.get('language'),
  urlBasis: state.get('urlBasis'),
  adBlock: state.get('adBlock'),
  paginationColumn: state.get('paginationColumn'),


  // --------------------------------------------------
  //   ヘッダー用
  // --------------------------------------------------

  gameNo: state.getIn(['headerObj', 'gameNo']),
  gameRenewalDate: state.getIn(['headerObj', 'gameRenewalDate']),
  gameId: state.getIn(['headerObj', 'gameId']),
  gameName: state.getIn(['headerObj', 'gameName']),
  gameSubtitle: state.getIn(['headerObj', 'gameSubtitle']),
  gameThumbnail: state.getIn(['headerObj', 'gameThumbnail']),
  gameReleaseDate1: state.getIn(['headerObj', 'gameReleaseDate1']),
  gameReleaseDate2: state.getIn(['headerObj', 'gameReleaseDate2']),
  gameReleaseDate3: state.getIn(['headerObj', 'gameReleaseDate3']),
  gameReleaseDate4: state.getIn(['headerObj', 'gameReleaseDate4']),
  gameReleaseDate5: state.getIn(['headerObj', 'gameReleaseDate5']),
  gamePlayersMax: state.getIn(['headerObj', 'gamePlayersMax']),
  gameHardwareObj: state.getIn(['headerObj', 'gameHardwareObj']),
  gameGenreObj: state.getIn(['headerObj', 'gameGenreObj']),
  gameDeveloperObj: state.getIn(['headerObj', 'gameDeveloperObj']),
  gameLinkObj: state.getIn(['headerObj', 'gameLinkObj']),


});



// --------------------------------------------------
//   mapDispatchToProps
// --------------------------------------------------

const mapDispatchToProps = (dispatch) => {

  const bindActionObj = bindActionCreators(actions, dispatch);

  return bindActionObj;

};



const ContainerRoot = connect(
  mapStateToProps,
  mapDispatchToProps
)(Root);



export default ContainerRoot;
