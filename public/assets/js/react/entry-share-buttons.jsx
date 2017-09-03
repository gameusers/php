// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import ReactDOM from 'react-dom';
import { createStore } from 'redux';
import { Provider } from 'react-redux';
import reducer from './reducers/reducer';
import ContainerRoot from './containers/root';


// --------------------------------------------------
//   Store
// --------------------------------------------------

const store = createStore(reducer);


// --------------------------------------------------
//   Render
// --------------------------------------------------

ReactDOM.render(
  <Provider store={store}>
    <ContainerRoot />
  </Provider>,
  document.querySelector('#gameusers-root')
);
