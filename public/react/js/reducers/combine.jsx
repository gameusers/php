// --------------------------------------------------
//   Import
// --------------------------------------------------

import { combineReducers } from 'redux';
import reducerRoot from './reducer';
import reducerApp from '../../contents/app/js/reducers/reducer';



// --------------------------------------------------
//   Combine Reducers
// --------------------------------------------------

const reducer = combineReducers({
  reducerRoot,
  reducerApp
});

export default reducer;
