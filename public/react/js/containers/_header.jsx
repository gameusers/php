import { connect } from 'react-redux';
// import { footerSelectBox } from '../actions';
import Header from '../components/header';

const mapStateToProps = state => ({
  userNo: state.userNo,
  playerId: state.playerId,
  urlBase: state.urlBase,
  imageId: state.headerObj.imageId,
  renewalDate: state.headerObj.renewalDate,
  communityName: state.headerObj.communityName,
  communityId: state.headerObj.communityId,
  gameNo: state.headerObj.gameNo,
  gameRenewalDate: state.headerObj.gameRenewalDate,
  gameId: state.headerObj.gameId,
  gameName: state.headerObj.gameName,
  gameSubtitle: state.headerObj.gameSubtitle,
  gameThumbnail: state.headerObj.gameThumbnail,
  hardwareObj: state.headerObj.hardwareObj,
  genreObj: state.headerObj.genreObj,
  playersMax: state.headerObj.playersMax,
  releaseDate1: state.headerObj.releaseDate1,
  releaseDate2: state.headerObj.releaseDate2,
  releaseDate3: state.headerObj.releaseDate3,
  releaseDate4: state.headerObj.releaseDate4,
  releaseDate5: state.headerObj.releaseDate5,
  developerObj: state.headerObj.developerObj,
  linkObj: state.headerObj.linkObj,
});

// function mapDispatchToProps(dispatch) {
//   return {
//     onClick(value) {
//       dispatch(footerSelectBox(value));
//     },
//   };
// }

const ContainerHeader = connect(
  mapStateToProps
)(Header);

export default ContainerHeader;
