// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';
import ReactDOM from 'react-dom';
import { createStore } from 'redux';
import { Provider } from 'react-redux';
// import reducer from './reducers/reducer';
import combineReducer from './reducers/combine';
import ContainerRoot from './containers/root';

// console.log('combine = ', combine(undefined, { type: null }));

// --------------------------------------------------
//   Store
// --------------------------------------------------

// const store = createStore(reducer);
const store = createStore(combineReducer);


// --------------------------------------------------
//   Render
// --------------------------------------------------

ReactDOM.render(
  <Provider store={store}>
    <ContainerRoot />
  </Provider>,
  document.querySelector('#gameusers-root')
);
