import React from 'react';
import ReactDOM from 'react-dom';
import { createStore } from 'redux';
import { Provider } from 'react-redux';
import footer from './reducers/footer';

// import { footerSelectBox } from './actions';
// import App from './components/app';
import ContainerApp from './containers/app';


// console.log(ContainerApp);

/* global initialStateJson */
const initialState = initialStateJson;
// console.log(initialState, initialState.headerObj.image_id);

// createStore（）メソッドを使ってStoreの作成
const store = createStore(footer, initialState);


// Rendering
ReactDOM.render(
  <Provider store={store}>
    <ContainerApp />
  </Provider>,
  document.querySelector('#root')
);


// setInterval(() => {
//   const nowTime = new Date(); // 現在日時を得る
//   const nowHour = nowTime.getHours(); // 時を抜き出す
//   const nowMin = nowTime.getMinutes(); // 分を抜き出す
//   const nowSec = nowTime.getSeconds(); // 秒を抜き出す
//   const msg = `現在時刻は、${nowHour}:${nowMin}:${nowSec} です。`;
//   store.dispatch(footerSelectBox(msg));
// }, 1000);
