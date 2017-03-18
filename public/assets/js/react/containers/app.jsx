import { connect } from 'react-redux';
// import { footerSelectBox } from '../actions';
import App from '../components/app';

// const mapStateToProps = state => ({
//   playerId: state.playerId,
//   uriBase: state.uriBase,
// });
//
// function mapDispatchToProps(dispatch) {
//   return {
//     onClick(value) {
//       dispatch(footerSelectBox(value));
//     },
//   };
// }
//
// const ContainerApp = connect(
//   mapStateToProps,
//   mapDispatchToProps
// )(App);


const ContainerApp = connect()(App);

export default ContainerApp;
