
// --------------------------------------------------
//   Action名の定義
// --------------------------------------------------

const FOOTER_SELECT_BOX = 'FOOTER_SELECT_BOX';


// --------------------------------------------------
//   Action Creators
// --------------------------------------------------

export const footerSelectBox = value => ({
  type: FOOTER_SELECT_BOX,
  value
});

export const actionCreatorReturnTop2 = () => ({
  type: FOOTER_SELECT_BOX
});

export const addTodo = text => ({
  type: 'ADD_TODO',
  id: text
});
// function actionCreator(value) {
//   // Action
//   return {
//     type: SEND,
//     value,
//   };
// }
