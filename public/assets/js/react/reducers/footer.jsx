
// --------------------------------------------------
//   Reducersの実装
// --------------------------------------------------

const footer = (state = {}, action) => {
  switch (action.type) {
    case 'FOOTER_SELECT_BOX':
      return Object.assign({}, state, {
        playerId: action.value,
      });
    default:
      return state;
  }
};

// function footer(state, action) {
//   switch (action.type) {
//     case 'SEND':
//       return Object.assign({}, state, {
//         value: action.value,
//       });
//     default:
//       return state;
//   }
// }

export default footer;
