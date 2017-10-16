// --------------------------------------------------
//   Import
// --------------------------------------------------

import { combineReducers } from 'redux';
import reducerRoot from '../js/reducers/reducer';


// ---------------------------------------------
//   - 新たにページを追加する場合は
//   ここで追加ページの Reducer を import する
// ---------------------------------------------

import reducerApp from './app/js/reducers/reducer';



// --------------------------------------------------
//   Combine Reducers
//   import した追加ページの Reducer をメインの Reducer と合成する
// --------------------------------------------------

const reducer = combineReducers({
  reducerRoot,
  reducerApp
});

export default reducer;
