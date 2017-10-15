// --------------------------------------------------
//   Import
// --------------------------------------------------

import { Model } from '../models/model';



const reducerApp = (state = new Model(), action) => {


  switch (action.type) {


    // --------------------------------------------------
    //   モーダル
    // --------------------------------------------------

    case 'TEST': {
      return state.setIn(['modalMap', 'notification', 'show'], action.value);
    }


    default: {
      return state;
    }

  }

};



export default reducerApp;
