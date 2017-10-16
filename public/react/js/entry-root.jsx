// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import ReactDOM from 'react-dom';
import { createStore } from 'redux';
import { Provider } from 'react-redux';
// import reducer from './reducers/reducer';
import reducer from '../contents/combine-reducers';
import ContainerRoot from './containers/root';

// console.log('combine = ', combine(undefined, { type: null }));

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
