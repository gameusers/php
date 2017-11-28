// --------------------------------------------------
//   Text 関数
// --------------------------------------------------

// --------------------------------------------------
//   Import
// --------------------------------------------------

import React from 'react';



/**
 * 文字数が指定した最大文字数よりも多い場合、カットして ... を追加する
 * @param  {string} str   テキスト
 * @param  {number} limit 最大文字数
 * @return {string}       変換済みのテキスト
 */
export const substrAndAddLeader = (str, limit) => {

  let returnValue = str;
  // console.log('str.length = ', str.length);
  if (str !== null && str.length > limit) {
    returnValue = `${str.substr(0, limit)}...`;
  }

  return returnValue;

};



/**
 * 改行を<br>に変換して返す / React用
 * @param  {string} str テキスト
 * @return {array || string}      変換済みのテキスト
 */
export const nl2brForReact = (str) => {

  if (str === null) {
    return str;
  }


  const resultArr = [];
  const splitArr = str.split('\n');
  const splitArrCount = splitArr.length;
  let count = 0;

  splitArr.forEach((value) => {

    count += 1;

    resultArr.push(value);

    if (count < splitArrCount) {
      resultArr.push(React.createElement('br', { key: count }));
    }

  });

  return resultArr;

};


// export default nl2brForReact;
